<?php

declare(strict_types=1);

namespace App\Http\Controllers\Komite;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\KomiteAspiration;
use App\Models\SchoolActionPlan;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $school = auth()->user()->school;

        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClasses = Classroom::count();

        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $recentAspirations = KomiteAspiration::latest()->take(5)->get();
        $actionPlans = SchoolActionPlan::latest()->take(5)->get();

        $programs = $actionPlans->map(function ($plan) {
            return [
                'name' => $plan->title ?? $plan->name ?? 'Program Sekolah',
                'description' => $plan->description ?? $plan->isi ?? '',
                'category' => $plan->category ?? 'Rencana Aksi',
                'status' => $plan->status ?? 'Aktif',
            ];
        })->toArray();

        return view('pages.komite.dashboard', compact(
            'school',
            'totalStudents',
            'totalTeachers',
            'totalClasses',
            'activeAcademicYear',
            'activeSemester',
            'recentAspirations',
            'programs'
        ));
    }
}
