<?php

declare(strict_types=1);

use App\Http\Controllers\KepalaSekolah;
use Illuminate\Support\Facades\Route;

// Prefix 'kepala-sekolah' dan name 'kepala-sekolah.' sudah diberikan di routes/web.php.
// Di sini hanya middleware tambahan.
Route::middleware(['auth', 'verified', 'role:kepala_sekolah'])
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [KepalaSekolah\DashboardController::class, 'index'])
        ->name('dashboard');

    // Monitoring Akademik
    Route::prefix('akademik')->name('academic.')->group(function () {
        Route::get('/rekap', [KepalaSekolah\AcademicController::class, 'rekap'])
            ->name('rekap');
        Route::get('/perkembangan', [KepalaSekolah\AcademicController::class, 'perkembangan'])
            ->name('perkembangan');
        Route::get('/mata-pelajaran', [KepalaSekolah\AcademicController::class, 'mataPelajaran'])
            ->name('subjects');
        Route::get('/siswa/{student}', [KepalaSekolah\AcademicController::class, 'studentDetail'])
            ->name('student-detail');
    });

    // Supervisi Kinerja Guru
    Route::prefix('supervisi')->name('supervision.')->group(function () {
        Route::get('/penilaian', [KepalaSekolah\SupervisionController::class, 'penilaianStatus'])
            ->name('grading-status');
        Route::get('/laporan-guru', [KepalaSekolah\SupervisionController::class, 'laporanGuru'])
            ->name('teacher-report');
        Route::get('/guru/{teacher}', [KepalaSekolah\SupervisionController::class, 'teacherDetail'])
            ->name('teacher-detail');
    });

    // Feedback Strategis (Top-down)
    Route::resource('feedback', KepalaSekolah\FeedbackController::class)
        ->except(['edit', 'update', 'destroy']);
    Route::patch('/feedback/{feedback}/status', [KepalaSekolah\FeedbackController::class, 'updateStatus'])
        ->name('feedback.update-status');

    // Rencana Aksi
    Route::resource('rencana-aksi', KepalaSekolah\ActionPlanController::class)
        ->except(['edit', 'update']);
    Route::patch('/rencana-aksi/{actionPlan}/status', [KepalaSekolah\ActionPlanController::class, 'updateStatus'])
        ->name('rencana-aksi.update-status');

    // Evaluasi Sekolah
    Route::resource('evaluasi', KepalaSekolah\EvaluationController::class)
        ->except(['edit', 'update', 'destroy']);

    // Laporan & Export
    Route::prefix('laporan')->name('reports.')->group(function () {
        Route::get('/', [KepalaSekolah\ReportController::class, 'index'])
            ->name('index');
        Route::get('/rekap-mingguan', [KepalaSekolah\ReportController::class, 'weeklyRecap'])
            ->name('weekly');
        Route::get('/rekap-bulanan', [KepalaSekolah\ReportController::class, 'monthlyRecap'])
            ->name('monthly');
        Route::get('/rekap-semester', [KepalaSekolah\ReportController::class, 'semesterRecap'])
            ->name('semester');
        Route::post('/approve', [KepalaSekolah\ReportController::class, 'approve'])
            ->name('approve');
        Route::get('/export/semester-pdf', [KepalaSekolah\ReportController::class, 'exportSemesterPdf'])
            ->name('export-semester-pdf');
        Route::get('/export/rekap-excel', [KepalaSekolah\ReportController::class, 'exportRekapExcel'])
            ->name('export-rekap-excel');
    });

    // Profil
    Route::get('/profil', [KepalaSekolah\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::put('/profil', [KepalaSekolah\ProfileController::class, 'update'])
        ->name('profile.update');
});
