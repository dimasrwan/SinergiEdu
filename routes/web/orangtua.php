<?php

declare(strict_types=1);

use App\Http\Controllers\OrangTua\DashboardController;
use App\Http\Controllers\OrangTua\FeedbackController;
use App\Http\Controllers\OrangTua\GradeController;
use App\Http\Controllers\OrangTua\SupportController;
use App\Http\Controllers\OrangTua\AssignmentController;
use App\Http\Controllers\OrangTua\ProgressController;

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');

Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
Route::get('/grades/{grade}', [GradeController::class, 'show'])->name('grades.show');

Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');

Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');
Route::get('/feedbacks/{feedback}', [FeedbackController::class, 'show'])->name('feedbacks.show');

Route::get('/support', [SupportController::class, 'index'])->name('support.index');
Route::post('/support', [SupportController::class, 'store'])->name('support.store');

