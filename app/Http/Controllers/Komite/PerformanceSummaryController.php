<?php

declare(strict_types=1);

namespace App\Http\Controllers\Komite;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentAssessment;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\View\View;

class PerformanceSummaryController extends Controller
{
    public function index(): View
    {
        $school = auth()->user()->school;

        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClasses = Classroom::count();
        $totalSubjects = Subject::count();

        // Calculate aggregate average score safely
        $averageScore = StudentAssessment::avg('average_score') ?? 0;

        return view('pages.komite.performance-summary.index', compact(
            'school',
            'activeAcademicYear',
            'activeSemester',
            'totalStudents',
            'totalTeachers',
            'totalClasses',
            'totalSubjects',
            'averageScore'
        ));
    }
}
