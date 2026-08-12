<?php

declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Rute Autentikasi (Breeze)
require __DIR__.'/auth.php';

// Rute berbasis peran (menggunakan middleware role)
Route::middleware(['auth'])->group(function () {
    
    // Rute Profil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Pengalihan dashboard umum jika user mengakses '/dashboard'
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return match ($user->role->name ?? null) {
            'admin' => redirect()->route('admin.dashboard'),
            'waka' => redirect()->route('waka.dashboard'),
            'guru' => redirect()->route('guru.dashboard'),
            'siswa' => redirect()->route('siswa.dashboard'),
            'orangtua' => redirect()->route('orangtua.dashboard'),
            'pengawas' => redirect()->route('pengawas.dashboard'),
            default => redirect('/'),
        };
    })->name('dashboard');

    // Admin
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(base_path('routes/web/admin.php'));
    
    // Waka Kurikulum
    Route::prefix('waka')->name('waka.')->middleware('role:waka')->group(base_path('routes/web/waka.php'));
    
    // Guru
    Route::prefix('guru')->name('guru.')->middleware('role:guru')->group(base_path('routes/web/guru.php'));
    
    // Siswa
    Route::prefix('siswa')->name('siswa.')->middleware('role:siswa')->group(base_path('routes/web/siswa.php'));
    
    // Orang Tua
    Route::prefix('orangtua')->name('orangtua.')->middleware('role:orangtua')->group(base_path('routes/web/orangtua.php'));
    
    // Pengawas
    Route::prefix('pengawas')->name('pengawas.')->middleware('role:pengawas')->group(base_path('routes/web/pengawas.php'));
});
