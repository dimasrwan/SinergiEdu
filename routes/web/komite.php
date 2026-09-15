<?php

declare(strict_types=1);

use App\Http\Controllers\Komite\AspirationController;
use App\Http\Controllers\Komite\DashboardController;
use App\Http\Controllers\Komite\PerformanceSummaryController;
use App\Http\Controllers\Komite\SchoolProfileController;
use App\Http\Controllers\Komite\SchoolProgramController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/school-profile', [SchoolProfileController::class, 'index'])->name('school-profile');
Route::get('/school-programs', [SchoolProgramController::class, 'index'])->name('school-programs');
Route::get('/performance-summary', [PerformanceSummaryController::class, 'index'])->name('performance-summary');

Route::prefix('aspirations')->name('aspirations.')->group(function () {
    Route::get('/', [AspirationController::class, 'index'])->name('index');
    Route::get('/create', [AspirationController::class, 'create'])->name('create');
    Route::post('/', [AspirationController::class, 'store'])->name('store');
    Route::get('/{aspiration}', [AspirationController::class, 'show'])->name('show');
});
