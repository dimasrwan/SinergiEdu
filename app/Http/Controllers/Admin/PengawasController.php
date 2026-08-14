<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PengawasRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\Pengawas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PengawasController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', \App\Models\Pengawas::class);
        $search = request('search');
        
        $pengawas = Pengawas::with(['user'])
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
            
        return view('pages.admin.pengawas.index', compact('pengawas'));
    }

    public function create(): View
    {
        Gate::authorize('create', \App\Models\Pengawas::class);
        return view('pages.admin.pengawas.create');
    }

    public function store(PengawasRequest $request): RedirectResponse
    {
        Gate::authorize('create', \App\Models\Pengawas::class);
        DB::transaction(function () use ($request) {
            // Find role pengawas
            $rolePengawas = Role::where('name', 'pengawas')->firstOrFail();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $rolePengawas->id,
            ]);

            Pengawas::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('admin.pengawas.index')->with('success', 'Data Pengawas berhasil ditambahkan.');
    }

    public function show(Pengawas $pengawas): View
    {
        Gate::authorize('view', $pengawas);
        $pengawas->load(['user']);
        return view('pages.admin.pengawas.show', compact('pengawas'));
    }

    public function edit(Pengawas $pengawas): View
    {
        Gate::authorize('update', $pengawas);
        $pengawas->load(['user']);
        return view('pages.admin.pengawas.edit', compact('pengawas'));
    }

    public function update(PengawasRequest $request, Pengawas $pengawas): RedirectResponse
    {
        Gate::authorize('update', $pengawas);
        DB::transaction(function () use ($request, $pengawas) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $pengawas->user->update($userData);

            $pengawas->update([
                'nip' => $request->nip,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('admin.pengawas.index')->with('success', 'Data Pengawas berhasil diperbarui.');
    }

    public function destroy(Pengawas $pengawas): RedirectResponse
    {
        Gate::authorize('delete', $pengawas);
        // Delete user, cascade will handle pengawas profile
        $pengawas->user->delete();
        
        return redirect()->route('admin.pengawas.index')->with('success', 'Data Pengawas berhasil dihapus.');
    }
}
