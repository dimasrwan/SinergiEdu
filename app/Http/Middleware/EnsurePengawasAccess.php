<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePengawasAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Bypass untuk rute pemilihan sekolah agar tidak looping
        if ($request->routeIs('pengawas.select-school') || $request->routeIs('pengawas.set-school')) {
            return $next($request);
        }

        $schoolId = session('pengawas_school_id');
        
        if (!$schoolId) {
            return redirect()->route('pengawas.select-school');
        }

        if (!$request->user()->assignedSchools()->where('school_id', $schoolId)->exists()) {
            abort(403, 'Anda tidak memiliki akses ke sekolah ini.');
        }

        return $next($request);
    }
}
