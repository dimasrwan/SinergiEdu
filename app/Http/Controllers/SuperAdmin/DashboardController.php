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
        $totalUsers = User::count(); // Super Admin can see all users, or rather sum of all schools' users. Since users with school_id=null are only super_admins, totalUsers is virtually all users.

        return view('pages.super-admin.dashboard', compact('totalSchools', 'activeSchools', 'totalUsers'));
    }
}
