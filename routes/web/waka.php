<?php

declare(strict_types=1);

use App\Http\Controllers\WakaKurikulum\DashboardController;
use App\Http\Controllers\WakaKurikulum\AcademicYearController;
use App\Http\Controllers\WakaKurikulum\SemesterController;
use App\Http\Controllers\WakaKurikulum\ClassroomController;
use App\Http\Controllers\WakaKurikulum\SubjectController;
use App\Http\Controllers\WakaKurikulum\MonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/analytics', function () {
    return view('pages.placeholder');
})->name('analytics.index');

// Toggle active routes
Route::patch('/academic-years/{academic_year}/toggle', [AcademicYearController::class, 'toggleActive'])->name('academic-years.toggle');
Route::patch('/semesters/{semester}/toggle', [SemesterController::class, 'toggleActive'])->name('semesters.toggle');

// Resources
Route::resource('/academic-years', AcademicYearController::class)->except(['show']);
Route::resource('/semesters', SemesterController::class)->except(['show']);
Route::resource('/classes', ClassroomController::class)->except(['show']);
Route::resource('/subjects', SubjectController::class)->except(['show']);

// Monitoring
Route::get('/monitoring/classes', [MonitoringController::class, 'classes'])->name('monitoring.classes');
Route::get('/monitoring/grades', [MonitoringController::class, 'grades'])->name('monitoring.grades');
Route::get('/monitoring/student-progress', [MonitoringController::class, 'studentProgress'])->name('monitoring.student-progress');
Route::get('/monitoring/evaluations', [MonitoringController::class, 'evaluations'])->name('monitoring.evaluations');
