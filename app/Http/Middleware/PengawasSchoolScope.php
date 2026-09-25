<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PengawasSchoolScope
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Hanya berlaku untuk role pengawas
        if ($user && $user->role && $user->role->name === 'pengawas') {
            
            // Kecuali rute pemilihan sekolah itu sendiri
            if ($request->routeIs('pengawas.select-school') || $request->routeIs('pengawas.set-school')) {
                return $next($request);
            }

            $schoolId = session('pengawas_school_id');

            // Jika belum pilih sekolah, redirect ke pemilih sekolah
            if (!$schoolId) {
                return redirect()->route('pengawas.select-school')->with('info', 'Silakan pilih sekolah yang ingin diawasi terlebih dahulu.');
            }

            // Validasi apakah sekolah tersebut di-assign ke pengawas ini
            $isAssigned = $user->assignedSchools()->where('schools.id', $schoolId)->exists();
            if (!$isAssigned) {
                session()->forget('pengawas_school_id');
                return redirect()->route('pengawas.select-school')->with('error', 'Anda tidak memiliki akses ke sekolah tersebut.');
            }
        }

        return $next($request);
    }
}
