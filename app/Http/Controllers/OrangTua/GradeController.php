<?php

declare(strict_types=1);

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\StudentParent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    private function getParentProfile(): StudentParent
    {
        return StudentParent::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(Request $request): View
    {
        $parent = $this->getParentProfile();
        $children = $parent->students()->with(['user', 'classes'])->get();
        $childIds = $children->pluck('id');

        $selectedStudentId = $request->query('student_id');

        if ($selectedStudentId) {
            $selectedStudent = $children->firstWhere('id', $selectedStudentId);
            if (!$selectedStudent) {
                $selectedStudent = $children->first();
                $selectedStudentId = $selectedStudent ? $selectedStudent->id : null;
            }
        } else {
            $selectedStudent = $children->first();
            $selectedStudentId = $selectedStudent ? $selectedStudent->id : null;
        }
        
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $grades = collect();
        $stats = [
            'rata_rata' => null,
            'jumlah_mapel' => 0,
            'tugas_dinilai' => '0 / 0',
        ];

        if ($selectedStudent && $classroom = $selectedStudent->activeClassroom()) {
            if ($activeYear && $activeSemester) {
                // Get grades for the selected child in the active context
                $grades = \App\Models\StudentGrade::where('student_id', $selectedStudentId)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester_id', $activeSemester->id)
                    ->with(['subject', 'teacher.user'])
                    ->get();
                
                $stats['jumlah_mapel'] = $grades->count();
                $avg = $grades->avg('average_score');
                if ($avg !== null) {
                    $stats['rata_rata'] = round((float)$avg, 1);
                }

                // Calculate assignments metrics globally for this class/context
                $totalAssignments = \App\Models\Assignment::where('class_id', $classroom->id)->count();
                $gradedSubmissions = \App\Models\AssignmentSubmission::where('student_id', $selectedStudentId)
                    ->whereHas('assignment', function ($q) use ($classroom) {
                        $q->where('class_id', $classroom->id);
                    })
                    ->whereNotNull('score')
                    ->count();
                
                $stats['tugas_dinilai'] = "{$gradedSubmissions} / {$totalAssignments}";
                
                // We also attach tugas_dinilai string to each grade item for subject cards
                // A better approach is to group assignments and submissions by subject_id
                $assignmentsPerSubject = \App\Models\Assignment::where('class_id', $classroom->id)
                    ->selectRaw('subject_id, COUNT(*) as total_assignments')
                    ->groupBy('subject_id')
                    ->pluck('total_assignments', 'subject_id');
                    
                $gradedPerSubject = \App\Models\AssignmentSubmission::where('student_id', $selectedStudentId)
                    ->whereNotNull('score')
                    ->whereHas('assignment', function ($q) use ($classroom) {
                        $q->where('class_id', $classroom->id);
                    })
                    ->join('assignments', 'assignment_submissions.assignment_id', '=', 'assignments.id')
                    ->selectRaw('assignments.subject_id, COUNT(*) as total_graded')
                    ->groupBy('assignments.subject_id')
                    ->pluck('total_graded', 'subject_id');
                
                foreach ($grades as $grade) {
                    $subId = $grade->subject_id;
                    $t = $assignmentsPerSubject[$subId] ?? 0;
                    $g = $gradedPerSubject[$subId] ?? 0;
                    $grade->tugas_dinilai_text = "{$g} / {$t}";
                }
            }
        }

        return view('pages.orangtua.grades.index', compact(
            'children',
            'selectedStudentId',
            'selectedStudent',
            'activeYear',
            'activeSemester',
            'grades',
            'stats'
        ));
    }

    public function show(\App\Models\StudentGrade $grade): View
    {
        $parent = $this->getParentProfile();
        $childIds = $parent->students()->pluck('students.id')->toArray();

        // Security Check: Ensure grade belongs to one of the parent's children
        if (!in_array($grade->student_id, $childIds, true)) {
            abort(403, 'Akses ditolak.');
        }

        $grade->load(['student.user', 'subject', 'teacher.user', 'academicYear', 'semester']);
        $student = $grade->student;
        $classroom = $student->activeClassroom(); // Assumes grade context matches active, if not fallback can be done
        
        // Find assignments for this subject and class
        // Technically, a grade record could be from past semesters, so we should filter assignments
        // by the same semester, but since assignments don't have semester_id, they are tied to class_id
        // which implies the semester. But for rigorous historical data, we need the class_id the student had *then*.
        // Since StudentClass links student to class per academic_year, we get it:
        $studentClass = \App\Models\StudentClass::where('student_id', $student->id)
            ->where('academic_year_id', $grade->academic_year_id)
            ->first();

        $assignments = collect();
        if ($studentClass) {
            $assignments = \App\Models\Assignment::where('class_id', $studentClass->class_id)
                ->where('subject_id', $grade->subject_id)
                ->with(['submissions' => function ($query) use ($student) {
                    $query->where('student_id', $student->id);
                }])
                ->get();
        }

        return view('pages.orangtua.grades.show', compact('grade', 'student', 'assignments', 'studentClass'));
    }
}
