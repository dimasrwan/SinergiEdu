<?php

declare(strict_types=1);

namespace App\Http\Controllers\WakaKurikulum;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\SchoolEvaluation;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    public function classes(): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        $classes = Classroom::all()->map(function ($class) use ($activeYear) {
            $studentsCount = $activeYear 
                ? $class->students()->where('student_classes.academic_year_id', $activeYear->id)->count() 
                : 0;

            // Hitung rata-rata nilai kelas
            $averageGrade = $activeYear 
                ? StudentGrade::where('class_id', $class->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->get()
                    ->avg(function ($grade) {
                        return $grade->average_score;
                    })
                : 0;

            $class->students_count = $studentsCount;
            $class->average_grade = $averageGrade ? round($averageGrade, 2) : 0;

            return $class;
        });

        return view('pages.waka.monitoring.classes', compact('classes', 'activeYear'));
    }

    public function grades(Request $request): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();
        
        $classes = Classroom::all();
        $subjects = Subject::all();

        $selectedClassId = $request->query('class_id');
        $selectedSubjectId = $request->query('subject_id');

        // Aggregation of 5 components
        $avgPreTest = 0;
        $avgAssignment = 0;
        $avgPostTest = 0;
        $avgCharacter = 0;
        $avgMemorization = 0;
        
        $gradesData = collect();

        if ($activeYear && $activeSemester) {
            $query = StudentGrade::where('academic_year_id', $activeYear->id)
                ->where('semester_id', $activeSemester->id);

            if ($selectedClassId) {
                $query->where('class_id', $selectedClassId);
            }

            if ($selectedSubjectId) {
                $query->where('subject_id', $selectedSubjectId);
            }

            $gradesData = $query->get();

            if ($gradesData->isNotEmpty()) {
                $avgPreTest = round($gradesData->whereNotNull('pre_test_score')->avg('pre_test_score') ?? 0, 1);
                $avgAssignment = round($gradesData->whereNotNull('assignment_score')->avg('assignment_score') ?? 0, 1);
                $avgPostTest = round($gradesData->whereNotNull('post_test_score')->avg('post_test_score') ?? 0, 1);
                $avgCharacter = round($gradesData->whereNotNull('character_score')->avg('character_score') ?? 0, 1);
                $avgMemorization = round($gradesData->whereNotNull('memorization_score')->avg('memorization_score') ?? 0, 1);
            }
        }

        // Data chart per subject for comparison
        $subjectComparison = [];
        if ($activeYear && $activeSemester) {
            $subjectComparison = Subject::all()->map(function ($subject) use ($activeYear, $activeSemester, $selectedClassId) {
                $q = StudentGrade::where('subject_id', $subject->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester_id', $activeSemester->id);
                if ($selectedClassId) {
                    $q->where('class_id', $selectedClassId);
                }
                $avg = $q->get()->avg(function ($g) { return $g->average_score; });
                return [
                    'name' => $subject->name,
                    'avg' => $avg ? round($avg, 1) : 0
                ];
            })->filter(function ($item) { return $item['avg'] > 0; })->values();
        }

        return view('pages.waka.monitoring.grades', compact(
            'classes', 'subjects', 'selectedClassId', 'selectedSubjectId',
            'avgPreTest', 'avgAssignment', 'avgPostTest', 'avgCharacter', 'avgMemorization',
            'subjectComparison', 'activeYear', 'activeSemester'
        ));
    }

    public function studentProgress(Request $request): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        $students = Student::with('user')->get()->sortBy('user.name');
        
        $selectedStudentId = $request->query('student_id');
        $studentGrades = collect();
        $selectedStudent = null;

        if ($selectedStudentId) {
            $selectedStudent = Student::with('user')->find($selectedStudentId);
            if ($selectedStudent && $activeYear) {
                $studentGrades = StudentGrade::where('student_id', $selectedStudentId)
                    ->where('academic_year_id', $activeYear->id)
                    ->with('subject')
                    ->get();
            }
        }

        return view('pages.waka.monitoring.student-progress', compact(
            'students', 'selectedStudentId', 'studentGrades', 'selectedStudent', 'activeYear'
        ));
    }

    public function evaluations(): View
    {
        $evaluations = SchoolEvaluation::with('user')->latest()->paginate(10);
        return view('pages.waka.monitoring.evaluations', compact('evaluations'));
    }
}
