<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WakaRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\Waka;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class WakaController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', \App\Models\Waka::class);
        $search = request('search');
        
        $wakas = Waka::with(['user'])
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
            
        return view('pages.admin.wakas.index', compact('wakas'));
    }

    public function create(): View
    {
        Gate::authorize('create', \App\Models\Waka::class);
        return view('pages.admin.wakas.create');
    }

    public function store(WakaRequest $request): RedirectResponse
    {
        Gate::authorize('create', \App\Models\Waka::class);
        DB::transaction(function () use ($request) {
            $roleWaka = Role::where('name', 'waka')->firstOrFail();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $roleWaka->id,
            ]);

            Waka::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('admin.wakas.index')->with('success', 'Data Waka Kurikulum berhasil ditambahkan.');
    }

    public function show(Waka $waka): View
    {
        Gate::authorize('view', $waka);
        $waka->load(['user']);
        return view('pages.admin.wakas.show', compact('waka'));
    }

    public function edit(Waka $waka): View
    {
        Gate::authorize('update', $waka);
        $waka->load(['user']);
        return view('pages.admin.wakas.edit', compact('waka'));
    }

    public function update(WakaRequest $request, Waka $waka): RedirectResponse
    {
        Gate::authorize('update', $waka);
        DB::transaction(function () use ($request, $waka) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $waka->user->update($userData);

            $waka->update([
                'nip' => $request->nip,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('admin.wakas.index')->with('success', 'Data Waka Kurikulum berhasil diperbarui.');
    }

    public function destroy(Waka $waka): RedirectResponse
    {
        Gate::authorize('delete', $waka);
        $waka->user->delete(); // Cascade will delete the Waka profile
        
        return redirect()->route('admin.wakas.index')->with('success', 'Data Waka Kurikulum berhasil dihapus.');
    }
}
