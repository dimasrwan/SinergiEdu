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

        return view('pages.super-admin.dashboard', compact('totalSchools', 'activeSchools', 'inactiveSchools', 'totalUsers', 'recentSchools'));
    }
}
