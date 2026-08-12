<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\TeacherAssignmentController;
use App\Http\Controllers\Admin\StudentPlacementController;
use App\Http\Controllers\Admin\WakaController;
use App\Http\Controllers\Admin\PengawasController;
use App\Http\Controllers\Admin\KepalaSekolahController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('/teachers', TeacherController::class);
Route::resource('/students', StudentController::class);
Route::resource('/parents', ParentController::class);
Route::resource('/classes', ClassroomController::class);
Route::resource('/subjects', SubjectController::class);
Route::resource('/academic-years', AcademicYearController::class);
Route::resource('/semesters', SemesterController::class);
Route::resource('/teacher-assignments', TeacherAssignmentController::class)->except(['show']);
Route::resource('/student-placements', StudentPlacementController::class)->except(['show']);
Route::resource('/wakas', WakaController::class);
Route::resource('/pengawas', PengawasController::class)->parameters([
    'pengawas' => 'pengawas'
]);
Route::resource('/kepala-sekolah', KepalaSekolahController::class)->parameters([
    'kepala-sekolah' => 'kepala_sekolah'
]);

Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
