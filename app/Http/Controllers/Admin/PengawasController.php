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
        
        $user = auth()->user();
        $isSchoolAdmin = $user && $user->role && $user->role->name === 'admin';

        $pengawas = Pengawas::with(['user.assignedSchools'])
            ->when($isSchoolAdmin, function ($query) use ($user) {
                $query->whereHas('user.assignedSchools', function ($q) use ($user) {
                    $q->where('schools.id', $user->school_id);
                });
            })
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
        $currentUser = auth()->user();
        $isSchoolAdmin = $currentUser && $currentUser->role && $currentUser->role->name === 'admin';

        $schools = \App\Models\School::where('is_active', true)
            ->when($isSchoolAdmin, function ($q) use ($currentUser) {
                $q->where('id', $currentUser->school_id);
            })
            ->orderBy('name')
            ->get();

        return view('pages.admin.pengawas.create', compact('schools'));
    }

    public function store(PengawasRequest $request): RedirectResponse
    {
        Gate::authorize('create', \App\Models\Pengawas::class);
        DB::transaction(function () use ($request) {
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

            // Assign sekolah ke pengawas
            if ($request->filled('schools')) {
                $user->assignedSchools()->sync($request->schools);
            }
        });

        return redirect()->route('admin.pengawas.index')->with('success', 'Data Pengawas berhasil ditambahkan.');
    }

    public function show(Pengawas $pengawas): View
    {
        Gate::authorize('view', $pengawas);
        $pengawas->load(['user.assignedSchools']);
        return view('pages.admin.pengawas.show', compact('pengawas'));
    }

    public function edit(Pengawas $pengawas): View
    {
        Gate::authorize('update', $pengawas);
        $pengawas->load(['user']);

        $currentUser = auth()->user();
        $isSchoolAdmin = $currentUser && $currentUser->role && $currentUser->role->name === 'admin';

        $schools = \App\Models\School::where('is_active', true)
            ->when($isSchoolAdmin, function ($q) use ($currentUser) {
                $q->where('id', $currentUser->school_id);
            })
            ->orderBy('name')
            ->get();

        $assignedSchoolIds = $pengawas->user->assignedSchools()->pluck('schools.id')->toArray();
        return view('pages.admin.pengawas.edit', compact('pengawas', 'schools', 'assignedSchoolIds'));
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

            $targetUser = User::findOrFail($pengawas->user_id);
            $targetUser->update($userData);

            Pengawas::updateOrCreate(
                ['user_id' => $pengawas->user_id],
                [
                    'nip' => $request->nip,
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]
            );

            // Sync sekolah yang di-assign
            $currentUser = auth()->user();
            $isSchoolAdmin = $currentUser && $currentUser->role && $currentUser->role->name === 'admin';

            $oldSchoolIds = $targetUser->assignedSchools()->pluck('schools.id')->toArray();
            $requestSchools = $request->schools ?? [];

            if ($isSchoolAdmin) {
                // Admin Sekolah can only change their own school's assignment, preserving other schools
                $otherAssignedSchools = $targetUser->assignedSchools()
                    ->where('schools.id', '!=', $currentUser->school_id)
                    ->pluck('schools.id')
                    ->toArray();

                $newSchools = array_unique(array_merge($otherAssignedSchools, $requestSchools));
                $targetUser->assignedSchools()->sync($newSchools);
            } else {
                $syncResult = $targetUser->assignedSchools()->sync($requestSchools);
                $attachedCount = count($syncResult['attached'] ?? []);
                $detachedCount = count($syncResult['detached'] ?? []);
                
                $msgParts = [];
                if ($attachedCount > 0) {
                    $msgParts[] = "$attachedCount sekolah ditugaskan";
                }
                if ($detachedCount > 0) {
                    $msgParts[] = "$detachedCount sekolah dilepas";
                }
                if (!empty($msgParts)) {
                    $request->session()->flash('info_detail', implode(', ', $msgParts) . '.');
                }
            }
        });

        $successMsg = 'Penugasan Pengawas berhasil diperbarui.';
        if (session('info_detail')) {
            $successMsg .= ' (' . session('info_detail') . ')';
        }

        return redirect()->route('admin.pengawas.index')->with('success', $successMsg);
    }

    public function destroy(Pengawas $pengawas): RedirectResponse
    {
        Gate::authorize('delete', $pengawas);
        // Delete user, cascade will handle pengawas profile
        $pengawas->user->delete();
        
        return redirect()->route('admin.pengawas.index')->with('success', 'Data Pengawas berhasil dihapus.');
    }

    /**
     * Form untuk Admin Sekolah menghubungkan Pengawas existing ke sekolahnya.
     */
    public function connectForm(): View
    {
        $user = auth()->user();
        $isSchoolAdmin = $user && $user->role && $user->role->name === 'admin';

        if (!$isSchoolAdmin) {
            abort(403, 'Halaman ini khusus untuk Admin Sekolah.');
        }

        $search = request('search');

        $availablePengawas = Pengawas::with(['user.assignedSchools'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                    })->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.pengawas.connect', compact('availablePengawas'));
    }

    /**
     * Action untuk Admin Sekolah menghubungkan Pengawas existing ke sekolahnya.
     */
    public function connect(): RedirectResponse
    {
        $user = auth()->user();
        $isSchoolAdmin = $user && $user->role && $user->role->name === 'admin';

        if (!$isSchoolAdmin || !$user->school_id) {
            abort(403, 'Hanya Admin Sekolah yang dapat menghubungkan Pengawas.');
        }

        request()->validate([
            'pengawas_id' => 'required|exists:pengawas,id',
        ]);

        $pengawas = Pengawas::findOrFail(request('pengawas_id'));
        $targetUser = $pengawas->user;

        if (!$targetUser || !$targetUser->role || $targetUser->role->name !== 'pengawas') {
            return redirect()->back()->withErrors(['pengawas_id' => 'Pengguna yang dipilih bukan Pengawas yang valid.']);
        }

        $alreadyConnected = $targetUser->assignedSchools()->where('schools.id', $user->school_id)->exists();
        if ($alreadyConnected) {
            return redirect()->route('admin.pengawas.index')->with('info', 'Pengawas sudah terhubung ke sekolah ini.');
        }

        $targetUser->assignedSchools()->syncWithoutDetaching([$user->school_id]);

        return redirect()->route('admin.pengawas.index')->with('success', 'Pengawas berhasil dihubungkan ke sekolah Anda.');
    }

    /**
     * Action untuk Admin Sekolah melepas Pengawas dari sekolahnya.
     */
    public function disconnect(Pengawas $pengawas): RedirectResponse
    {
        $user = auth()->user();
        $isSchoolAdmin = $user && $user->role && $user->role->name === 'admin';

        if (!$isSchoolAdmin || !$user->school_id) {
            abort(403, 'Hanya Admin Sekolah yang dapat melepas Pengawas.');
        }

        $targetUser = $pengawas->user;
        if (!$targetUser || !$targetUser->assignedSchools()->where('schools.id', $user->school_id)->exists()) {
            abort(403, 'Pengawas ini tidak terhubung dengan sekolah Anda.');
        }

        $targetUser->assignedSchools()->detach($user->school_id);

        return redirect()->route('admin.pengawas.index')->with('success', 'Pengawas berhasil dilepas dari sekolah.');
    }
}
