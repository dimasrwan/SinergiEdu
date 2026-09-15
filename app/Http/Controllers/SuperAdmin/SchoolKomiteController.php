<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SchoolKomiteController extends Controller
{
    public function create(School $school): View
    {
        return view('pages.super-admin.schools.komite.create', compact('school'));
    }

    public function store(Request $request, School $school): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $komiteRole = Role::where('name', 'komite')->firstOrFail();

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $komiteRole->id,
            'school_id' => $school->id,
            'is_active' => true,
        ]);

        return redirect()->route('super_admin.schools.show', $school)
            ->with('success', 'Akun Komite Sekolah berhasil ditambahkan.');
    }

    public function show(School $school, User $komite): View
    {
        $komiteRole = Role::where('name', 'komite')->firstOrFail();

        if ($komite->school_id !== $school->id || $komite->role_id !== $komiteRole->id) {
            abort(404, 'Komite tidak ditemukan untuk sekolah ini.');
        }

        return view('pages.super-admin.schools.komite.show', compact('school', 'komite'));
    }

    public function edit(School $school, User $komite): View
    {
        $komiteRole = Role::where('name', 'komite')->firstOrFail();

        if ($komite->school_id !== $school->id || $komite->role_id !== $komiteRole->id) {
            abort(404, 'Komite tidak ditemukan untuk sekolah ini.');
        }

        return view('pages.super-admin.schools.komite.edit', compact('school', 'komite'));
    }

    public function update(Request $request, School $school, User $komite): RedirectResponse
    {
        $komiteRole = Role::where('name', 'komite')->firstOrFail();

        if ($komite->school_id !== $school->id || $komite->role_id !== $komiteRole->id) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($komite->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $komite->update($data);

        return redirect()->route('super_admin.schools.show', $school)
            ->with('success', 'Data Komite Sekolah berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, School $school, User $komite): RedirectResponse
    {
        $komiteRole = Role::where('name', 'komite')->firstOrFail();

        if ($komite->school_id !== $school->id || $komite->role_id !== $komiteRole->id) {
            abort(404);
        }

        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $komite->update([
            'is_active' => $request->boolean('is_active'),
        ]);

        $statusText = $komite->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Akses Komite Sekolah berhasil $statusText.");
    }
}
