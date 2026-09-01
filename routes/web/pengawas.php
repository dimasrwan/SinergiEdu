<?php

declare(strict_types=1);

use App\Http\Controllers\Pengawas\ActionPlanController;
use App\Http\Controllers\Pengawas\DashboardController;
use App\Http\Controllers\Pengawas\EvaluationController;
use App\Http\Controllers\Pengawas\FeedbackController;
use App\Http\Controllers\Pengawas\InspectionController;
use App\Http\Controllers\Pengawas\PerformanceReportController;
use App\Http\Controllers\Pengawas\StudentMonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Monitoring Siswa
Route::get('/students', [StudentMonitoringController::class, 'index'])->name('students.index');
Route::get('/students/{student}', [StudentMonitoringController::class, 'show'])->name('students.show');

// Feedback Pengawas ke Siswa
Route::post('/students/{student}/feedback', [FeedbackController::class, 'store'])->name('students.feedback.store');
Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');

// Evaluasi Sekolah
Route::resource('/evaluations', EvaluationController::class);

// Rencana Aksi
Route::resource('/action-plans', ActionPlanController::class)->except(['show']);

// Laporan Kinerja
Route::resource('/reports', PerformanceReportController::class);

// Jadwal Inspeksi
Route::resource('/inspections', InspectionController::class);

