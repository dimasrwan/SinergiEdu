<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SchoolAdminController extends Controller
{
    /**
     * Tampilkan form pembuatan Admin Sekolah.
     */
    public function create(School $school)
    {
        return view('pages.super-admin.schools.admins.create', compact('school'));
    }

    /**
     * Proses penyimpanan Admin Sekolah.
     */
    public function store(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = $request->input('email');
        
        // Cek existing user
        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            $existingUser->load('role');
            
            // Case A: User sudah Admin di sekolah yang sama
            if ($existingUser->role->name === 'admin' && $existingUser->school_id === $school->id) {
                return back()->withInput()->withErrors([
                    'email' => 'Admin dengan email tersebut sudah terdaftar di sekolah ini.'
                ]);
            }
            
            // Case B: User adalah admin sekolah lain
            if ($existingUser->role->name === 'admin' && $existingUser->school_id !== $school->id) {
                return back()->withInput()->withErrors([
                    'email' => 'Akun ini sudah terikat ke sekolah lain.'
                ]);
            }

            // Case C: User memiliki role lain
            return back()->withInput()->withErrors([
                'email' => "Akun ini sudah terdaftar dengan role '{$existingUser->role->display_name}'. Tidak dapat mengubah role secara otomatis."
            ]);
        }

        // Ambil role admin
        $adminRole = Role::where('name', 'admin')->firstOrFail();

        // Buat user
        User::create([
            'name' => $request->input('name'),
            'email' => $email,
            'password' => Hash::make($request->input('password')),
            'role_id' => $adminRole->id,
            'school_id' => $school->id,
            'is_active' => true,
        ]);

        return redirect()->route('super_admin.schools.show', $school)
            ->with('success', 'Admin Sekolah berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail Admin Sekolah.
     */
    public function show(School $school, User $admin)
    {
        // Validasi bahwa user ini benar-benar milik sekolah yang dilalui dari route
        if ($admin->school_id !== $school->id || $admin->role->name !== 'admin') {
            abort(404, 'Admin tidak ditemukan untuk sekolah ini.');
        }

        return view('pages.super-admin.schools.admins.show', compact('school', 'admin'));
    }

    /**
     * Tampilkan form edit Admin Sekolah.
     */
    public function edit(School $school, User $admin)
    {
        if ($admin->school_id !== $school->id || $admin->role->name !== 'admin') {
            abort(404, 'Admin tidak ditemukan untuk sekolah ini.');
        }

        return view('pages.super-admin.schools.admins.edit', compact('school', 'admin'));
    }

    /**
     * Proses pembaruan data Admin Sekolah.
     */
    public function update(Request $request, School $school, User $admin)
    {
        if ($admin->school_id !== $school->id || $admin->role->name !== 'admin') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $admin->update($data);

        return redirect()->route('super_admin.schools.show', $school)
            ->with('success', 'Data Admin Sekolah berhasil diperbarui.');
    }

    /**
     * Toggle akses / status Admin Sekolah.
     */
    public function toggleStatus(Request $request, School $school, User $admin)
    {
        if ($admin->school_id !== $school->id || $admin->role->name !== 'admin') {
            abort(404);
        }

        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $admin->update([
            'is_active' => $request->is_active,
        ]);

        $statusText = $admin->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Akses Admin Sekolah berhasil $statusText.");
    }
}
