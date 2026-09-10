<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserMonitoringController extends Controller
{
    /**
     * List user di sekolah aktif berdasarkan role.
     */
    public function index(Request $request): View
    {
        $schoolId = session('pengawas_school_id');
        $search = $request->query('search');
        $roleFilter = $request->query('role');

        // Roles yang dipantau (semua kecuali super_admin)
        $allowedRoles = Role::whereNotIn('name', ['super_admin', 'superadmin'])->get();

        $users = User::query()
            ->where('school_id', $schoolId)
            ->whereHas('role', function ($q) {
                $q->whereNotIn('name', ['super_admin', 'superadmin']);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($roleFilter, function ($q) use ($roleFilter) {
                $q->whereHas('role', function ($query) use ($roleFilter) {
                    $query->where('name', $roleFilter);
                });
            })
            ->with('role')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('pages.pengawas.users.index', compact('users', 'allowedRoles'));
    }

    /**
     * Detail profile user yang dipantau.
     */
    public function show(User $user): View
    {
        $schoolId = session('pengawas_school_id');

        // Pastikan user berada di sekolah yang sama dan bukan super_admin
        if ($user->school_id != $schoolId || ($user->role && in_array($user->role->name, ['super_admin', 'superadmin']))) {
            abort(403, 'Anda tidak memiliki akses untuk memantau user ini.');
        }

        $user->load(['role', 'school']);

        return view('pages.pengawas.users.show', compact('user'));
    }
}
