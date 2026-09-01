<?php

declare(strict_types=1);

use App\Http\Controllers\Pengawas\DashboardController;
use App\Http\Controllers\Pengawas\EvaluationController;
use App\Http\Controllers\Pengawas\StudentMonitoringController;
use App\Http\Controllers\Pengawas\FeedbackController;
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

// Evaluasi
Route::resource('/evaluations', EvaluationController::class);

Route::get('/reports', function () {
    return view('pages.placeholder');
})->name('reports.index');

Route::get('/inspections', function () {
    return view('pages.placeholder');
})->name('inspections.index');
