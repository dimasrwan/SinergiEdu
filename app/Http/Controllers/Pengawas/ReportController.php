<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        $totalStudents = \App\Models\Student::count();
        $totalTeachers = \App\Models\Teacher::count();
        $totalSchools = \App\Models\School::count();

        $grades = \App\Models\StudentGrade::query()
            ->when($activeYear, fn ($q) => $q->where('academic_year_id', $activeYear?->id))
            ->when($activeSemester, fn ($q) => $q->where('semester_id', $activeSemester?->id));

        $avgScore = $grades->avg(DB::raw('(COALESCE(pre_test_score, 0) + COALESCE(assignment_score, 0) + COALESCE(post_test_score, 0) + COALESCE(character_score, 0) + COALESCE(memorization_score, 0)) / 5'));
        $totalGrades = $grades->count();
        $feedbackGiven = $grades->whereNotNull('supervisor_feedback')->count();

        $evaluations = \App\Models\SchoolEvaluation::query()
            ->when($activeYear, fn ($q) => $q->whereYear('created_at', $activeYear?->year))
            ->latest()
            ->take(5)
            ->get();

        $inspections = \App\Models\Inspection::query()
            ->when($activeYear, fn ($q) => $q->whereYear('inspection_date', $activeYear?->year))
            ->latest()
            ->take(5)
            ->get();

        return view('pages.pengawas.reports.index', compact(
            'activeYear', 'activeSemester',
            'totalStudents', 'totalTeachers', 'totalSchools',
            'avgScore', 'totalGrades', 'feedbackGiven',
            'evaluations', 'inspections'
        ));
    }
}