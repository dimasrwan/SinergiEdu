<?php

declare(strict_types=1);

use App\Http\Controllers\OrangTua\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/progress', function () {
    return view('pages.placeholder');
})->name('progress.index');

Route::get('/grades', [\App\Http\Controllers\OrangTua\GradeController::class, 'index'])->name('grades.index');

Route::get('/assignments', function () {
    return view('pages.placeholder');
})->name('assignments.index');

Route::get('/feedbacks', [\App\Http\Controllers\OrangTua\FeedbackController::class, 'index'])->name('feedbacks.index');

Route::get('/support', function () {
    return view('pages.placeholder');
})->name('support.index');
