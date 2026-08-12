<?php

declare(strict_types=1);

use App\Http\Controllers\Pengawas\DashboardController;
use App\Http\Controllers\Pengawas\EvaluationController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/reports', function () {
    return view('pages.placeholder');
})->name('reports.index');

Route::resource('/evaluations', EvaluationController::class);

Route::get('/inspections', function () {
    return view('pages.placeholder');
})->name('inspections.index');
