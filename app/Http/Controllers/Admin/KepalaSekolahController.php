<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KepalaSekolahRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\KepalaSekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class KepalaSekolahController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', \App\Models\KepalaSekolah::class);
        $search = request('search');
        
        $kepalaSekolah = KepalaSekolah::with(['user'])
            ->when($search, function ($query) use ($search) {
                $query->where(function($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('nip', 'like', "%{$search}%");
            });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
            
        return view('pages.admin.kepala-sekolah.index', compact('kepalaSekolah'));
    }

    public function create(): View
    {
        Gate::authorize('create', \App\Models\KepalaSekolah::class);
        return view('pages.admin.kepala-sekolah.create');
    }

    public function store(KepalaSekolahRequest $request): RedirectResponse
    {
        Gate::authorize('create', \App\Models\KepalaSekolah::class);
        DB::transaction(function () use ($request) {
            $roleKepsek = Role::where('name', 'kepala_sekolah')->firstOrFail();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $roleKepsek->id,
            ]);

            KepalaSekolah::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('admin.kepala-sekolah.index')->with('success', 'Data Kepala Sekolah/Madrasah berhasil ditambahkan.');
    }

    public function show(KepalaSekolah $kepalaSekolah): View
    {
        Gate::authorize('view', $kepalaSekolah);
        $kepalaSekolah->load(['user']);
        return view('pages.admin.kepala-sekolah.show', compact('kepalaSekolah'));
    }

    public function edit(KepalaSekolah $kepalaSekolah): View
    {
        Gate::authorize('update', $kepalaSekolah);
        $kepalaSekolah->load(['user']);
        return view('pages.admin.kepala-sekolah.edit', compact('kepalaSekolah'));
    }

    public function update(KepalaSekolahRequest $request, KepalaSekolah $kepalaSekolah): RedirectResponse
    {
        Gate::authorize('update', $kepalaSekolah);
        DB::transaction(function () use ($request, $kepalaSekolah) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $kepalaSekolah->user->update($userData);

            $kepalaSekolah->update([
                'nip' => $request->nip,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('admin.kepala-sekolah.index')->with('success', 'Data Kepala Sekolah/Madrasah berhasil diperbarui.');
    }

    public function destroy(KepalaSekolah $kepalaSekolah): RedirectResponse
    {
        Gate::authorize('delete', $kepalaSekolah);
        $kepalaSekolah->user->delete();
        
        return redirect()->route('admin.kepala-sekolah.index')->with('success', 'Data Kepala Sekolah/Madrasah berhasil dihapus.');
    }
}
