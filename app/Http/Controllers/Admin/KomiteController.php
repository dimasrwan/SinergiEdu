<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KomiteController extends Controller
{
    public function index(): View
    {
        $search = request('search');

        $komiteRole = Role::where('name', 'komite')->firstOrFail();

        $totalKomite = User::where('role_id', $komiteRole->id)
            ->where('school_id', auth()->user()->school_id)
            ->count();

        $komiteUsers = User::where('role_id', $komiteRole->id)
            ->where('school_id', auth()->user()->school_id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.komite.index', compact('komiteUsers', 'totalKomite'));
    }

    public function create(): View
    {
        return view('pages.admin.komite.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'nullable|boolean',
        ]);

        $komiteRole = Role::where('name', 'komite')->firstOrFail();

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $komiteRole->id,
            'school_id' => auth()->user()->school_id, // FORCED from tenant context!
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.komite.index')
            ->with('success', 'Akun Komite Sekolah berhasil ditambahkan.');
    }

    public function show(User $komite): View
    {
        $komiteRole = Role::where('name', 'komite')->firstOrFail();

        if ($komite->school_id !== auth()->user()->school_id || $komite->role_id !== $komiteRole->id) {
            abort(404, 'Data Komite Sekolah tidak ditemukan.');
        }

        return view('pages.admin.komite.show', compact('komite'));
    }

    public function edit(User $komite): View
    {
        $komiteRole = Role::where('name', 'komite')->firstOrFail();

        if ($komite->school_id !== auth()->user()->school_id || $komite->role_id !== $komiteRole->id) {
            abort(404, 'Data Komite Sekolah tidak ditemukan.');
        }

        return view('pages.admin.komite.edit', compact('komite'));
    }

    public function update(Request $request, User $komite): RedirectResponse
    {
        $komiteRole = Role::where('name', 'komite')->firstOrFail();

        if ($komite->school_id !== auth()->user()->school_id || $komite->role_id !== $komiteRole->id) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($komite->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $komite->update($data);

        return redirect()->route('admin.komite.index')
            ->with('success', 'Data Komite Sekolah berhasil diperbarui.');
    }

    public function destroy(User $komite): RedirectResponse
    {
        $komiteRole = Role::where('name', 'komite')->firstOrFail();

        if ($komite->school_id !== auth()->user()->school_id || $komite->role_id !== $komiteRole->id) {
            abort(404);
        }

        $komite->delete();

        return redirect()->route('admin.komite.index')
            ->with('success', 'Akun Komite Sekolah berhasil dihapus.');
    }
}
