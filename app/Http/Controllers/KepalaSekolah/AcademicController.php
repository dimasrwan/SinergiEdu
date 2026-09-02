<?php

declare(strict_types=1);

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\Subject;
use App\Services\KepalaSekolah\AcademicAggregatorService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicController extends Controller
{
    public function rekap(Request $request, AcademicAggregatorService $aggregator): View
    {
        $classes = Classroom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $semesters = Semester::with('academicYear')->get();

        $classId = $request->input('class_id');
        $subjectId = $request->input('subject_id');
        $semesterId = $request->input('semester_id', $aggregator->activeSemester()?->id);

        $rows = $aggregator->getRekapList(
            auth()->user()->school_id,
            null,
            $semesterId,
            $classId,
            $subjectId,
        );

        return view('pages.kepala-sekolah.academic.rekap', compact(
            'classes', 'subjects', 'semesters', 'classId', 'subjectId', 'semesterId', 'rows'
        ));
    }

    public function perkembangan(AcademicAggregatorService $aggregator): View
    {
        $students = Student::with('user')->get();
        $classes = Classroom::orderBy('name')->get();
        $selectedStudent = request()->input('student_id');
        $classId = request()->input('class_id');

        $studentList = $students;
        if ($classId) {
            $studentList = Student::whereHas('classes', fn ($q) => $q->where('classes.id', $classId))
                ->with('user')
                ->get();
        }

        $rows = collect([]);
        if ($selectedStudent) {
            $grades = StudentGrade::with(['subject', 'semester'])
                ->where('student_id', $selectedStudent)
                ->get();

            $student = Student::with('user')->find($selectedStudent);
            $rows = $grades->groupBy('subject_id')->map(function ($subjectGrades, $subjectId) {
                $first = $subjectGrades->first();
                return (object) [
                    'subject_name' => $first->subject->name,
                    'avg' => round($subjectGrades->avg(fn ($g) => $g->average_score) ?? 0, 2),
                    'avg_pre_test' => round($subjectGrades->avg('pre_test_score') ?? 0, 1),
                    'avg_assignment' => round($subjectGrades->avg('assignment_score') ?? 0, 1),
                    'avg_post_test' => round($subjectGrades->avg('post_test_score') ?? 0, 1),
                    'avg_character' => round($subjectGrades->avg('character_score') ?? 0, 1),
                    'avg_memorization' => round($subjectGrades->avg('memorization_score') ?? 0, 1),
                    'grades' => $subjectGrades,
                ];
            })->values();
        } else {
            $student = null;
        }

        $allStudentGrades = StudentGrade::with('student.user')
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->get()
            ->groupBy('student_id')
            ->map(function ($grades, $studentId) {
                $first = $grades->first();
                return (object) [
                    'student_id' => $studentId,
                    'name' => $first->student->user->name,
                    'avg' => round($grades->avg(fn ($g) => $g->average_score) ?? 0, 2),
                    'avg_character' => round($grades->avg('character_score') ?? 0, 1),
                    'avg_memorization' => round($grades->avg('memorization_score') ?? 0, 1),
                ];
            })->values();

        $topStudents = $allStudentGrades->sortByDesc('avg')->take(10);
        $attentionStudents = $allStudentGrades->sortBy('avg')->take(5);

        return view('pages.kepala-sekolah.academic.perkembangan', compact(
            'students', 'classes', 'selectedStudent', 'classId', 'studentList', 'rows', 'student', 'topStudents', 'attentionStudents'
        ));
    }

    public function mataPelajaran(AcademicAggregatorService $aggregator): View
    {
        $subjectAnalysis = $aggregator->getSubjectAnalysis(auth()->user()->school_id);

        return view('pages.kepala-sekolah.academic.mata-pelajaran', compact('subjectAnalysis'));
    }

    public function studentDetail(Student $student): View
    {
        $grades = StudentGrade::with(['subject', 'semester.academicYear'])
            ->where('student_id', $student->id)
            ->get();

        $subjectRows = $grades->groupBy('subject_id')->map(function ($subjectGrades, $subjectId) {
            $first = $subjectGrades->first();
            return (object) [
                'subject_name' => $first->subject->name,
                'avg' => round($subjectGrades->avg(fn ($g) => $g->average_score) ?? 0, 2),
                'avg_pre_test' => round($subjectGrades->avg('pre_test_score') ?? 0, 1),
                'avg_assignment' => round($subjectGrades->avg('assignment_score') ?? 0, 1),
                'avg_post_test' => round($subjectGrades->avg('post_test_score') ?? 0, 1),
                'avg_character' => round($subjectGrades->avg('character_score') ?? 0, 1),
                'avg_memorization' => round($subjectGrades->avg('memorization_score') ?? 0, 1),
                'grades' => $subjectGrades,
            ];
        })->values();

        return view('pages.kepala-sekolah.academic.student-detail', compact('student', 'subjectRows'));
    }
}
