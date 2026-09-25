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
        $schoolId = (int) auth()->user()->school_id;

        $classes = Classroom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $semesters = Semester::with('academicYear')->orderBy('id')->get();

        $classId = $request->filled('class_id') ? (int) $request->input('class_id') : null;
        $subjectId = $request->filled('subject_id') ? (int) $request->input('subject_id') : null;

        if ($request->has('semester_id')) {
            $rawSemesterId = $request->input('semester_id');
            if ($rawSemesterId === null || $rawSemesterId === '') {
                $semesterId = null;
            } else {
                $semesterId = (int) $rawSemesterId;
                $isValidSemester = Semester::where('id', $semesterId)->exists();
                if (! $isValidSemester) {
                    abort(404);
                }
            }
        } else {
            $activeSemester = $aggregator->activeSemester();
            $semesterId = $activeSemester?->id ? (int) $activeSemester->id : null;
        }

        if ($classId && ! Classroom::where('id', $classId)->exists()) {
            abort(404);
        }

        if ($subjectId && ! Subject::where('id', $subjectId)->exists()) {
            abort(404);
        }

        $rows = $aggregator->getRekapList(
            $schoolId,
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
        $schoolId = (int) auth()->user()->school_id;

        $classes = Classroom::orderBy('name')->get();
        $selectedStudent = request()->filled('student_id') ? (int) request()->input('student_id') : null;
        $classId = request()->filled('class_id') ? (int) request()->input('class_id') : null;

        if ($classId && ! Classroom::where('id', $classId)->exists()) {
            abort(404);
        }

        $studentList = Student::with('user')
            ->when($classId, fn ($q) => $q->whereHas('classes', fn ($q2) => $q2->where('classes.id', $classId)))
            ->get();

        $rows = collect([]);
        $student = null;
        if ($selectedStudent) {
            $studentQuery = Student::with('user')->where('id', $selectedStudent);
            if ($classId) {
                $studentQuery->whereHas('classes', fn ($q) => $q->where('classes.id', $classId));
            }
            $student = $studentQuery->first();

            if (! $student) {
                abort(404);
            }

            $grades = StudentGrade::with(['subject', 'semester'])
                ->where('student_id', $selectedStudent)
                ->get();

            $rows = $grades->groupBy('subject_id')->map(function ($subjectGrades, $subjectId) {
                $first = $subjectGrades->first();
                return (object) [
                    'subject_name' => $first->subject?->name ?? '-',
                    'avg' => round($subjectGrades->avg(fn ($g) => $g->average_score) ?? 0, 2),
                    'avg_pre_test' => round($subjectGrades->avg('pre_test_score') ?? 0, 1),
                    'avg_assignment' => round($subjectGrades->avg('assignment_score') ?? 0, 1),
                    'avg_post_test' => round($subjectGrades->avg('post_test_score') ?? 0, 1),
                    'avg_character' => round($subjectGrades->avg('character_score') ?? 0, 1),
                    'avg_memorization' => round($subjectGrades->avg('memorization_score') ?? 0, 1),
                    'grades' => $subjectGrades,
                ];
            })->values();
        }

        $allStudentGrades = StudentGrade::with('student.user')
            ->whereHas('student')
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->get()
            ->groupBy('student_id')
            ->map(function ($grades, $studentId) {
                $first = $grades->first();
                return (object) [
                    'student_id' => $studentId,
                    'name' => $first->student?->user?->name ?? 'Siswa Tidak Diketahui',
                    'avg' => round($grades->avg(fn ($g) => $g->average_score) ?? 0, 2),
                    'avg_character' => round($grades->avg('character_score') ?? 0, 1),
                    'avg_memorization' => round($grades->avg('memorization_score') ?? 0, 1),
                ];
            })->values();

        $topStudents = $allStudentGrades->sortByDesc('avg')->take(10)->values();
        $attentionStudents = $allStudentGrades->sortBy('avg')->take(5)->values();

        return view('pages.kepala-sekolah.academic.perkembangan', compact(
            'classes', 'selectedStudent', 'classId', 'studentList', 'rows', 'student', 'topStudents', 'attentionStudents'
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
