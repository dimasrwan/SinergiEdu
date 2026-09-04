<?php

declare(strict_types=1);

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\StudentParent;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProgressController extends Controller
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
        $classAverages = [];
        $assignmentProgress = [];

        if ($selectedStudent && $classId = $selectedStudent->classes()->first()?->id) {
            if ($activeYear && $activeSemester) {
                $grades = StudentGrade::where('student_id', $selectedStudentId)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester_id', $activeSemester->id)
                    ->with(['subject', 'teacher.user'])
                    ->get();

                // Hitung rata-rata kelas menggunakan satu query aggregasi
                $classGradesAgg = StudentGrade::where('class_id', $classId)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester_id', $activeSemester->id)
                    ->select(
                        'subject_id',
                        DB::raw('AVG(pre_test_score) as pre_test'),
                        DB::raw('AVG(assignment_score) as assignment'),
                        DB::raw('AVG(post_test_score) as post_test'),
                        DB::raw('AVG(character_score) as character_avg'),
                        DB::raw('AVG(memorization_score) as memorization')
                    )
                    ->groupBy('subject_id')
                    ->get()
                    ->keyBy('subject_id');

                $subjectAssignmentsCount = Assignment::where('class_id', $classId)
                    ->select('subject_id', DB::raw('count(*) as total'))
                    ->groupBy('subject_id')
                    ->pluck('total', 'subject_id');

                $submittedAssignmentsCount = AssignmentSubmission::where('student_id', $selectedStudentId)
                    ->join('assignments', 'assignments.id', '=', 'assignment_submissions.assignment_id')
                    ->where('assignments.class_id', $classId)
                    ->select('assignments.subject_id', DB::raw('count(*) as total'))
                    ->groupBy('assignments.subject_id')
                    ->pluck('total', 'subject_id');

                foreach ($grades as $grade) {
                    $avgData = $classGradesAgg->get($grade->subject_id);
                    $classAverages[$grade->subject_id] = [
                        'pre_test' => $avgData ? round((float)$avgData->pre_test, 1) : 0,
                        'assignment' => $avgData ? round((float)$avgData->assignment, 1) : 0,
                        'post_test' => $avgData ? round((float)$avgData->post_test, 1) : 0,
                        'character' => $avgData ? round((float)$avgData->character_avg, 1) : 0,
                        'memorization' => $avgData ? round((float)$avgData->memorization, 1) : 0,
                    ];

                    $assignmentProgress[$grade->subject_id] = [
                        'total' => $subjectAssignmentsCount->get($grade->subject_id) ?? 0,
                        'submitted' => $submittedAssignmentsCount->get($grade->subject_id) ?? 0,
                    ];
                }
            }
        }

        return view('pages.orangtua.progress.index', compact(
            'children',
            'selectedStudentId',
            'selectedStudent',
            'grades',
            'classAverages',
            'assignmentProgress',
            'activeYear',
            'activeSemester'
        ));
    }
}
