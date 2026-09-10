<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\TenantService;
use Illuminate\Support\Facades\Auth;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login
        if (Auth::check()) {
            $user = Auth::user();

            // Load role untuk mengecek super admin
            if (!$user->relationLoaded('role')) {
                $user->load('role');
            }

            if ($user->role && $user->role->name === 'super_admin') {
                // Platform context untuk Super Admin
                app(\App\Services\TenantService::class)->setPlatformContext();
            } elseif ($user->role && $user->role->name === 'pengawas') {
                // Untuk Pengawas, ambil school_id dari session
                $schoolId = session('pengawas_school_id');
                
                if ($schoolId) {
                    $school = \App\Models\School::find($schoolId);
                    if ($school && $school->is_active) {
                        app(\App\Services\TenantService::class)->setSchool($school);
                    }
                }
                // Jika tidak ada schoolId di session, TenantService tetap kosong 
                // dan middleware PengawasSchoolScope yang akan menangani redirect.
            } else {
                // Pastikan user memiliki school_id
                if (!$user->school_id) {
                    abort(403, 'Forbidden: You do not have an associated school.');
                }

                // Pastikan school valid
                $school = $user->school;
                if (!$school) {
                    abort(403, 'Forbidden: Associated school does not exist.');
                }

                if (!$school->is_active) {
                    abort(403, 'Forbidden: Your school is inactive.');
                }

                // Set current tenant context
                app(TenantService::class)->setSchool($school);
            }
        }

        return $next($request);
    }
}
