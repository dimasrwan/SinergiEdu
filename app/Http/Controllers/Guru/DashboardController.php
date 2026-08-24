<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\Semester;
use App\Models\StudentClass;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private function getTeacherProfile(): Teacher
    {
        return Teacher::where('user_id', auth()->id())->firstOrFail();
    }

    /**
     * Tampilkan halaman dashboard Guru.
     */
    public function index(): View
    {
        $teacher = $this->getTeacherProfile();

        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $missingContext = !$activeAcademicYear || !$activeSemester;

        // Initialize variables
        $activeClassesCount = 0;
        $unscoredTasksCount = 0;
        $totalStudentsCount = 0;
        $activeClasses = collect();
        $needGradingAssignments = collect();
        
        // Sum of all unscored submissions across all tasks (for welcome message)
        $totalUnscoredSubmissions = 0;

        if (!$missingContext) {
            // Get teacher's assignments in current context (TeacherSubject)
            $teacherSubjects = TeacherSubject::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('semester_id', $activeSemester->id)
                ->get();

            // Unique classes taught
            $activeClassIds = $teacherSubjects->pluck('class_id')->unique();
            $activeClassesCount = $activeClassIds->count();

            // Unique students taught in those active classes
            $totalStudentsCount = StudentClass::whereIn('class_id', $activeClassIds)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->distinct('student_id')
                ->count('student_id');

            // Unique active subjects for the active classes
            $activeSubjectIds = $teacherSubjects->pluck('subject_id')->unique();
            
            // Get assignments created by this teacher for these active classes and subjects
            $queryAssignments = Assignment::where('teacher_id', $teacher->id)
                ->whereIn('class_id', $activeClassIds)
                ->whereIn('subject_id', $activeSubjectIds);

            // Need Grading Assignments (with count of unscored submissions)
            $needGradingAssignments = (clone $queryAssignments)
                ->with(['classroom', 'subject'])
                ->whereHas('submissions', function ($query) {
                    $query->whereNull('score');
                })
                ->withCount(['submissions as unscored_submissions_count' => function ($query) {
                    $query->whereNull('score');
                }])
                ->orderByDesc('created_at')
                ->take(4)
                ->get();
                
            // Total unscored submissions across all assignments for the welcome message
            $totalUnscoredSubmissions = $needGradingAssignments->sum('unscored_submissions_count');
            
            // Card stat: number of assignments that have unscored submissions
            $unscoredTasksCount = $needGradingAssignments->count(); // since we already queried it, but wait, needGradingAssignments is limited to 4!
            
            // To get accurate total count without limit:
            $unscoredTasksCount = (clone $queryAssignments)
                ->whereHas('submissions', function ($query) {
                    $query->whereNull('score');
                })
                ->count();
                
            // And accurate total submissions count
            $totalUnscoredSubmissions = \App\Models\AssignmentSubmission::whereNull('score')
                ->whereHas('assignment', function ($q) use ($teacher, $activeClassIds, $activeSubjectIds) {
                    $q->where('teacher_id', $teacher->id)
                      ->whereIn('class_id', $activeClassIds)
                      ->whereIn('subject_id', $activeSubjectIds);
                })
                ->count();

            // List of active classes for the "Kelas Aktif Saya" section
            $activeClasses = TeacherSubject::with(['classroom' => function ($query) {
                    $query->withCount('students');
                }, 'subject'])
                ->where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('semester_id', $activeSemester->id)
                ->take(4)
                ->get();
        }

        return view('pages.guru.dashboard', compact(
            'missingContext',
            'activeClassesCount',
            'unscoredTasksCount',
            'totalStudentsCount',
            'activeClasses',
            'needGradingAssignments',
            'totalUnscoredSubmissions',
            'activeAcademicYear',
            'activeSemester'
        ));
    }
}
