<?php

declare(strict_types=1);

use App\Http\Controllers\Siswa\AssignmentController;
use App\Http\Controllers\Siswa\DashboardController;
use App\Http\Controllers\Siswa\MaterialController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');



Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
Route::get('/materials/{material}', [MaterialController::class, 'show'])->name('materials.show');

Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
Route::get('/assignments/{assignment}/download', [AssignmentController::class, 'download'])->name('assignments.download');
Route::get('/assignments/{assignment}/submissions/download', [AssignmentController::class, 'downloadSubmission'])->name('assignments.submissions.download');
Route::post('/assignments/{assignment}/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');

Route::get('/grades', [\App\Http\Controllers\Siswa\GradeController::class, 'index'])->name('grades.index');
Route::get('/grades/{grade}', [\App\Http\Controllers\Siswa\GradeController::class, 'show'])->name('grades.show');

Route::get('/feedbacks', [\App\Http\Controllers\Siswa\FeedbackController::class, 'index'])->name('feedbacks.index');
Route::get('/feedbacks/{feedback}', [\App\Http\Controllers\Siswa\FeedbackController::class, 'show'])->name('feedbacks.show');

Route::get('/reflections', function () {
    return view('pages.placeholder');
})->name('reflections.index');
