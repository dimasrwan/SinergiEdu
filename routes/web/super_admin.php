<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\SchoolController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('schools')->name('schools.')->group(function () {
    Route::get('/', [SchoolController::class, 'index'])->name('index');
    Route::get('/create', [SchoolController::class, 'create'])->name('create');
    Route::post('/', [SchoolController::class, 'store'])->name('store');
    Route::get('/{school}', [SchoolController::class, 'show'])->name('show');
    Route::get('/{school}/edit', [SchoolController::class, 'edit'])->name('edit');
    Route::put('/{school}', [SchoolController::class, 'update'])->name('update');
    Route::patch('/{school}/toggle-status', [SchoolController::class, 'toggleStatus'])->name('toggle-status');

    // Nested resources for School Admins
    Route::prefix('{school}/admins')->name('admins.')->group(function () {
        Route::get('/create', [\App\Http\Controllers\SuperAdmin\SchoolAdminController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\SchoolAdminController::class, 'store'])->name('store');
        Route::get('/{admin}', [\App\Http\Controllers\SuperAdmin\SchoolAdminController::class, 'show'])->name('show');
        Route::get('/{admin}/edit', [\App\Http\Controllers\SuperAdmin\SchoolAdminController::class, 'edit'])->name('edit');
        Route::put('/{admin}', [\App\Http\Controllers\SuperAdmin\SchoolAdminController::class, 'update'])->name('update');
        Route::patch('/{admin}/toggle-status', [\App\Http\Controllers\SuperAdmin\SchoolAdminController::class, 'toggleStatus'])->name('toggle-status');
    });
});
