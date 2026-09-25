<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard Super Admin.
     */
    public function index()
    {
        $totalSchools = School::count();
        $activeSchools = School::where('is_active', true)->count();
        $inactiveSchools = School::where('is_active', false)->count();
        $totalUsers = User::count(); 

        $recentSchools = School::withCount('users')->latest()->take(5)->get();

        // Monitoring Pengawas & Coverage Sekolah
        $pengawasRole = \App\Models\Role::where('name', 'pengawas')->first();
        $pengawasRoleId = $pengawasRole?->id;

        $totalPengawas = $pengawasRoleId ? User::withoutGlobalScopes()->where('role_id', $pengawasRoleId)->count() : 0;
        $activePengawas = $pengawasRoleId ? User::withoutGlobalScopes()->where('role_id', $pengawasRoleId)->where('is_active', true)->count() : 0;
        
        $unassignedPengawas = $pengawasRoleId ? User::withoutGlobalScopes()
            ->where('role_id', $pengawasRoleId)
            ->where('is_active', true)
            ->whereDoesntHave('assignedSchools')
            ->count() : 0;

        $totalAssignments = \Illuminate\Support\Facades\DB::table('pengawas_school')->count();

        $coveredSchoolsCount = School::where('is_active', true)
            ->whereHas('supervisors')
            ->count();

        $uncoveredSchoolsCount = max(0, $activeSchools - $coveredSchoolsCount);
        $coveragePercentage = $activeSchools > 0 ? round(($coveredSchoolsCount / $activeSchools) * 100, 1) : 0;

        return view('pages.super-admin.dashboard', compact(
            'totalSchools',
            'activeSchools',
            'inactiveSchools',
            'totalUsers',
            'recentSchools',
            'totalPengawas',
            'activePengawas',
            'unassignedPengawas',
            'totalAssignments',
            'coveredSchoolsCount',
            'uncoveredSchoolsCount',
            'coveragePercentage'
        ));
    }
}
