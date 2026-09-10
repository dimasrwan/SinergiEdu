<?php

declare(strict_types=1);

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Feedback;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\KepalaSekolah\AcademicAggregatorService;
use App\Services\KepalaSekolah\SupervisionService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(AcademicAggregatorService $aggregator, SupervisionService $supervision): View
    {
        $activeYear = $aggregator->activeYear();
        $activeSemester = $aggregator->activeSemester();

        $totalTeachers = Teacher::count();
        $totalStudents = Student::count();
        $totalClasses = Classroom::count();
        $schoolAvgGrade = $aggregator->getSchoolAverageGrade(auth()->user()->school_id);
        $componentAverages = $aggregator->getComponentAverages(auth()->user()->school_id);
        $classRankings = $aggregator->getClassRankings(auth()->user()->school_id);
        $subjectAnalysis = $aggregator->getSubjectAnalysis(auth()->user()->school_id);

        $gradingStatus = $supervision->getGradingStatus();
        $lateGrading = $gradingStatus->where('status', 'pending')->count();
        $completedGrading = $gradingStatus->where('status', 'completed')->count();
        $totalAssignments = $gradingStatus->count();

        $feedbacksCount = Feedback::where('sender_id', auth()->id())->count();
        $actionPlanCount = \App\Models\SchoolActionPlan::where('user_id', auth()->id())->count();

        $belowTargetClasses = $classRankings->where('avg', '>', 0)
            ->filter(fn ($row) => $row['avg'] < 75)
            ->take(5);

        $teacherReports = $supervision->getTeacherReports();
        $topTeachers = $teacherReports->take(5);
        $lowestSubjects = $subjectAnalysis->sortBy('avg')->take(5);

        return view('pages.kepala-sekolah.dashboard', compact(
            'activeYear',
            'activeSemester',
            'totalTeachers',
            'totalStudents',
            'totalClasses',
            'schoolAvgGrade',
            'componentAverages',
            'classRankings',
            'subjectAnalysis',
            'lateGrading',
            'completedGrading',
            'totalAssignments',
            'feedbacksCount',
            'actionPlanCount',
            'belowTargetClasses',
            'topTeachers',
            'lowestSubjects',
            'gradingStatus',
        ));
    }
}
