<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Services\TenantService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    /**
     * Tampilkan daftar sekolah.
     */
    public function index(Request $request, \App\Services\SchoolDeletionEligibilityService $eligibilityService)
    {
        $query = School::withCount('users');

        // Pencarian (Global Search/Lokal Search di halaman ini)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('npsn', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'aktif') {
                $query->where('is_active', true);
            } elseif ($status === 'nonaktif') {
                $query->where('is_active', false);
            }
        }

        $schools = $query->latest()->paginate(10)->withQueryString();

        // Calculate deletion eligibility for each school on current page
        $eligibilityMap = [];
        foreach ($schools as $schoolItem) {
            $eligibilityMap[$schoolItem->id] = $eligibilityService->check($schoolItem);
        }

        return view('pages.super-admin.schools.index', compact('schools', 'eligibilityMap'));
    }

    /**
     * Tampilkan form untuk menambah sekolah.
     */
    public function create()
    {
        return view('pages.super-admin.schools.create');
    }

    /**
     * Simpan sekolah baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'npsn' => 'nullable|string|max:20|unique:schools,npsn',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->only(['name', 'npsn', 'email', 'phone', 'address', 'is_active']);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            // Pastikan aman
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $data['logo'] = $file->storeAs('schools/logos', $filename, 'public');
        }

        School::create($data);

        return redirect()->route('super_admin.schools.index')
            ->with('success', 'Sekolah berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail sekolah tertentu.
     */
    public function show(School $school, \App\Services\SchoolDeletionEligibilityService $eligibilityService)
    {
        $school->loadCount([
            'users', 
            'teachers', 
            'students', 
            'classrooms'
        ]);

        $school->load(['supervisors.pengawas']);

        // Available pengawas for connect modal (pengawas users not yet attached to this school)
        $attachedUserIds = $school->supervisors->pluck('id')->toArray();
        $availablePengawas = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'pengawas'))
            ->whereNotIn('id', $attachedUserIds)
            ->where('is_active', true)
            ->get();

        // Daftar admin untuk sekolah ini
        $admins = $school->users()->whereHas('role', function($q) {
            $q->where('name', 'admin');
        })->get();

        $deletionEligibility = $eligibilityService->check($school);

        return view('pages.super-admin.schools.show', compact('school', 'admins', 'availablePengawas', 'deletionEligibility'));
    }

    /**
     * Tampilkan form edit sekolah.
     */
    public function edit(School $school)
    {
        $pengawas = \App\Models\User::whereHas('role', function($q) {
            $q->where('name', 'pengawas');
        })->get();
        return view('pages.super-admin.schools.edit', compact('school', 'pengawas'));
    }

    /**
     * Update data sekolah.
     */
    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'npsn' => ['nullable', 'string', 'max:20', Rule::unique('schools')->ignore($school->id)],
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'is_active' => 'required|boolean',
            'pengawas_ids' => 'nullable|array',
            'pengawas_ids.*' => 'exists:users,id',
        ]);

        $data = $request->only(['name', 'npsn', 'email', 'phone', 'address', 'is_active']);

        $oldLogo = $school->logo;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('schools/logos', $filename, 'public');
            
            if ($path) {
                $data['logo'] = $path;
            }
        }

        try {
            $school->update($data);
            
            if ($request->has('pengawas_ids')) {
                $school->supervisors()->sync($request->pengawas_ids);
            } else {
                $school->supervisors()->sync([]);
            }
            
            if ($request->hasFile('logo') && $oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
        } catch (\Exception $e) {
            if (isset($data['logo']) && Storage::disk('public')->exists($data['logo'])) {
                Storage::disk('public')->delete($data['logo']);
            }
            throw $e;
        }

        return redirect()->route('super_admin.schools.index')
            ->with('success', 'Data Sekolah berhasil diperbarui.');
    }

    /**
     * Toggle status aktif/nonaktif.
     */
    public function toggleStatus(Request $request, School $school)
    {
        // Validasi input
        $request->validate([
            'is_active' => 'required|boolean',
        ]);
        
        $school->update([
            'is_active' => $request->is_active
        ]);

        $statusText = $school->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Sekolah berhasil $statusText.");
    }

    /**
     * Hubungkan Pengawas ke Sekolah (tanpa merusak assignment sekolah lain).
     */
    public function attachSupervisor(Request $request, School $school)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = \App\Models\User::where('id', $request->user_id)
            ->whereHas('role', fn($q) => $q->where('name', 'pengawas'))
            ->firstOrFail();

        // Attach safely without detaching existing schools
        $school->supervisors()->syncWithoutDetaching([$user->id]);

        return redirect()->back()->with('success', 'Pengawas berhasil dihubungkan ke sekolah ini.');
    }

    /**
     * Lepas Pengawas dari Sekolah (hanya menghapus pivot sekolah ini).
     */
    public function detachSupervisor(School $school, \App\Models\User $user)
    {
        $school->supervisors()->detach($user->id);

        return redirect()->back()->with('success', 'Pengawas berhasil dilepas dari sekolah ini.');
    }

    /**
     * Hapus permanen sekolah jika eligible (0 dependency data penting).
     */
    public function destroy(Request $request, School $school, \App\Services\SchoolDeletionEligibilityService $eligibilityService)
    {
        $check = $eligibilityService->check($school);

        if (!$check['eligible']) {
            return redirect()->back()->with('error', 'Sekolah tidak dapat dihapus permanen karena masih memiliki data terkait: ' . implode(', ', $check['reasons']));
        }

        // Type-to-confirm verification
        $request->validate([
            'confirm_school_name' => 'required|string',
        ]);

        if (trim($request->input('confirm_school_name')) !== trim($school->name)) {
            return redirect()->back()->with('error', 'Konfirmasi nama sekolah tidak cocok. Penghapusan dibatalkan.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($school) {
            // 1. Detach all supervisor pivot relations (pengawas_school) - leaves Pengawas users intact!
            $school->supervisors()->detach();

            // 2. Clean logo storage file if exists
            if ($school->logo && Storage::disk('public')->exists($school->logo)) {
                Storage::disk('public')->delete($school->logo);
            }

            // 3. Delete settings if any
            $school->settings()->delete();

            // 4. Delete the school record
            $school->delete();
        });

        return redirect()->route('super_admin.schools.index')->with('success', "Sekolah '{$school->name}' berhasil dihapus permanen.");
    }
}
