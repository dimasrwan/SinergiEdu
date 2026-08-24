<?php

declare(strict_types=1);

use App\Http\Controllers\Guru\AssignmentController;
use App\Http\Controllers\Guru\DashboardController;
use App\Http\Controllers\Guru\FeedbackController;
use App\Http\Controllers\Guru\GradeController;
use App\Http\Controllers\Guru\MaterialController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Guru\ClassroomController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/classes', [ClassroomController::class, 'index'])->name('classes.index');
Route::get('/classes/{class}', [ClassroomController::class, 'show'])->name('classes.show');

Route::get('/materials/{material}/download', [MaterialController::class, 'download'])->name('materials.download');
Route::resource('/materials', MaterialController::class);
Route::get('/assignments/{assignment}/download', [AssignmentController::class, 'download'])->name('assignments.download');
Route::get('/assignments/{assignment}/submissions/{submission}/download', [AssignmentController::class, 'downloadSubmission'])->name('assignments.submissions.download');
    Route::post('/assignments/{assignment}/submissions/{submission}/grade', [AssignmentController::class, 'grade'])
        ->name('assignments.submissions.grade');
    Route::post('/assignments/{assignment}/submissions/{submission}/feedback', [AssignmentController::class, 'feedback'])
        ->name('assignments.submissions.feedback');
Route::resource('/assignments', AssignmentController::class);
Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
Route::resource('/feedbacks', FeedbackController::class);

use App\Http\Controllers\Guru\StudentProgressController;

Route::get('/student-progress', [StudentProgressController::class, 'index'])->name('student-progress.index');
Route::get('/student-progress/{student}', [StudentProgressController::class, 'show'])->name('student-progress.show');
