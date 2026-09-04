<?php

declare(strict_types=1);

use App\Http\Controllers\Pengawas\DashboardController;
use App\Http\Controllers\Pengawas\EvaluationController;
use App\Http\Controllers\Pengawas\FeedbackController;
use App\Http\Controllers\Pengawas\InspectionController;
use App\Http\Controllers\Pengawas\ReportController;
use App\Http\Controllers\Pengawas\StudentMonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Monitoring Siswa & Hasil Belajar
Route::get('/students', [StudentMonitoringController::class, 'index'])->name('students.index');
Route::get('/students/{student}', [StudentMonitoringController::class, 'show'])->name('students.show');
Route::get('/students/download/report', [StudentMonitoringController::class, 'downloadReport'])->name('students.downloadReport');

// Feedback & Rencana Aksi
Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
Route::get('/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
Route::get('/feedback/{student}/edit', [FeedbackController::class, 'edit'])->name('feedback.edit');
Route::put('/feedback/{student}', [FeedbackController::class, 'update'])->name('feedback.update');
Route::put('/feedback/{student}/archive', [FeedbackController::class, 'archive'])->name('feedback.archive');
Route::put('/feedback/{student}/unarchive', [FeedbackController::class, 'unarchive'])->name('feedback.unarchive');
Route::get('/feedback/archived', [FeedbackController::class, 'archived'])->name('feedback.archived');

// Evaluasi
Route::resource('/evaluations', EvaluationController::class);
Route::put('/evaluations/{evaluation}/archive', [EvaluationController::class, 'archive'])->name('evaluations.archive');
Route::put('/evaluations/{evaluation}/unarchive', [EvaluationController::class, 'unarchive'])->name('evaluations.unarchive');
Route::get('/evaluations/archived', [EvaluationController::class, 'archived'])->name('evaluations.archived');

// Laporan Kinerja
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/archived', [ReportController::class, 'archived'])->name('reports.archived');

// Jadwal Inspeksi
Route::resource('/inspections', InspectionController::class);
Route::put('/inspections/{inspection}/archive', [InspectionController::class, 'archive'])->name('inspections.archive');
Route::put('/inspections/{inspection}/unarchive', [InspectionController::class, 'unarchive'])->name('inspections.unarchive');
Route::get('/inspections/archived', [InspectionController::class, 'archived'])->name('inspections.archived');
