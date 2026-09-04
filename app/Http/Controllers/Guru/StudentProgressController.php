<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentGrade;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentProgressController extends Controller
{
    private function getTeacherProfile(): Teacher
    {
        return Teacher::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(Request $request): View
    {
        $teacher = $this->getTeacherProfile();
        
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();
        
        if (!$activeAcademicYear || !$activeSemester) {
            return view('pages.guru.student-progress.index', [
                'hasActiveContext' => false,
                'students' => collect(),
            ]);
        }

        $teacherSubjects = TeacherSubject::with(['classroom', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->where('semester_id', $activeSemester->id)
            ->get();
            
        $availableClasses = $teacherSubjects->pluck('classroom')->unique('id')->values();
        $availableSubjects = $teacherSubjects->pluck('subject')->unique('id')->values();

        $filterClassId = $request->input('class_id');
        $filterSubjectId = $request->input('subject_id');
        $search = $request->input('search');

        $combinationsQuery = clone $teacherSubjects;
        if ($filterClassId) {
            $combinationsQuery = $combinationsQuery->where('class_id', $filterClassId);
        }
        if ($filterSubjectId) {
            $combinationsQuery = $combinationsQuery->where('subject_id', $filterSubjectId);
        }

        $studentList = collect();
        
        $totalSiswa = 0;
        $totalRataRata = 0;
        $totalSelesai = 0;
        $totalBelumDinilai = 0;
        $countRataRata = 0;

        foreach ($combinationsQuery as $ts) {
            $studentIds = StudentClass::where('class_id', $ts->class_id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->pluck('student_id');
                
            $studentsQuery = Student::with(['user'])
                ->whereIn('id', $studentIds);

            if ($search) {
                $studentsQuery->where(function($q) use ($search) {
                    $q->whereHas('user', function($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })->orWhere('nis', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%");
                });
            }

            $students = $studentsQuery->get();
            $totalSiswa += $students->count();

            $totalAssignments = Assignment::where('class_id', $ts->class_id)
                ->where('subject_id', $ts->subject_id)
                ->count();
            
            $assignmentIds = Assignment::where('class_id', $ts->class_id)
                ->where('subject_id', $ts->subject_id)
                ->pluck('id');

            foreach ($students as $student) {
                $studentGrade = StudentGrade::where('student_id', $student->id)
                    ->where('subject_id', $ts->subject_id)
                    ->where('academic_year_id', $activeAcademicYear->id)
                    ->where('semester_id', $activeSemester->id)
                    ->first();
                    
                $avgScore = $studentGrade ? $studentGrade->assignment_score : null;
                if ($avgScore !== null) {
                    $totalRataRata += $avgScore;
                    $countRataRata++;
                }

                $submissions = AssignmentSubmission::where('student_id', $student->id)
                    ->whereIn('assignment_id', $assignmentIds)
                    ->get();
                    
                $completed = $submissions->count();
                $ungraded = $submissions->whereNull('score')->count();
                
                $totalSelesai += $completed;
                $totalBelumDinilai += $ungraded;
                
                $status = 'Lengkap';
                if ($completed < $totalAssignments) {
                    $status = 'Ada Tunggakan Tugas';
                }

                $studentList->push((object)[
                    'id' => $student->id,
                    'student' => $student,
                    'classroom' => $ts->classroom,
                    'subject' => $ts->subject,
                    'avg_score' => $avgScore,
                    'completed_assignments' => $completed,
                    'total_assignments' => $totalAssignments,
                    'ungraded' => $ungraded,
                    'status' => $status,
                ]);
            }
        }
        
        $overview = [
            'total_siswa' => $totalSiswa,
            'rata_rata' => $countRataRata > 0 ? round($totalRataRata / $countRataRata, 1) : 0,
            'tugas_selesai' => $totalSelesai,
            'belum_dinilai' => $totalBelumDinilai
        ];

        $perPage = 15;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $items = $studentList->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedStudents = new \Illuminate\Pagination\LengthAwarePaginator($items, $studentList->count(), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        return view('pages.guru.student-progress.index', [
            'hasActiveContext' => true,
            'students' => $paginatedStudents,
            'classes' => $availableClasses,
            'subjects' => $availableSubjects,
            'overview' => $overview,
        ]);
    }

    public function show(Request $request, Student $student)
    {
        $teacher = $this->getTeacherProfile();
        
        $activeAcademicYear = AcademicYear::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::where('is_active', true)->firstOrFail();
        
        $subjectId = $request->input('subject_id');
        $classId = $request->input('class_id');
        
        abort_if(!$subjectId || !$classId, 400, 'Parameter subject_id dan class_id diperlukan.');

        $isValidContext = TeacherSubject::where('teacher_id', $teacher->id)
            ->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->where('semester_id', $activeSemester->id)
            ->exists();
            
        abort_if(!$isValidContext, 403, 'Anda tidak berhak melihat data perkembangan ini.');
        
        $studentInClass = StudentClass::where('student_id', $student->id)
            ->where('class_id', $classId)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->exists();
            
        abort_if(!$studentInClass, 403, 'Siswa tidak terdaftar pada kelas ini.');

        $subject = \App\Models\Subject::findOrFail($subjectId);
        $classroom = \App\Models\Classroom::findOrFail($classId);
        $student->load('user');

        $assignments = Assignment::where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->with(['submissions' => function($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
            ->orderBy('deadline', 'asc')
            ->get();
            
        $studentGrade = StudentGrade::where('student_id', $student->id)
            ->where('subject_id', $subjectId)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->where('semester_id', $activeSemester->id)
            ->first();
            
        $avgScore = $studentGrade ? $studentGrade->assignment_score : null;

        return view('pages.guru.student-progress.show', compact('student', 'subject', 'classroom', 'assignments', 'avgScore'));
    }
}
