<?php

declare(strict_types=1);

use App\Http\Controllers\Guru\AssignmentController;
use App\Http\Controllers\Guru\DashboardController;
use App\Http\Controllers\Guru\FeedbackController;
use App\Http\Controllers\Guru\GradeController;
use App\Http\Controllers\Guru\LearningMeetingController;
use App\Http\Controllers\Guru\MaterialController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/classes', function () {
    return view('pages.placeholder');
})->name('classes.index');

Route::resource('/materials', MaterialController::class);
Route::resource('/learning-meetings', LearningMeetingController::class)->only(['index', 'create', 'store']);
Route::resource('/assignments', AssignmentController::class);
Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
Route::resource('/feedbacks', FeedbackController::class);

Route::get('/student-progress', function () {
    return view('pages.placeholder');
})->name('student-progress.index');
