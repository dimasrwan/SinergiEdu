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
    public function index(Request $request)
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

        return view('pages.super-admin.schools.index', compact('schools'));
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
    public function show(School $school)
    {
        $school->loadCount([
            'users', 
            'teachers', 
            'students', 
            'classrooms'
        ]);

        // Daftar admin untuk sekolah ini (dengan menggunakan with('role') untuk efisiensi, 
        // tapi kita filter user yang punya role 'admin' & 'school_id' = ini.
        $admins = $school->users()->whereHas('role', function($q) {
            $q->where('name', 'admin');
        })->get();

        return view('pages.super-admin.schools.show', compact('school', 'admins'));
    }

    /**
     * Tampilkan form edit sekolah.
     */
    public function edit(School $school)
    {
        return view('pages.super-admin.schools.edit', compact('school'));
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
        ]);

        $data = $request->only(['name', 'npsn', 'email', 'phone', 'address', 'is_active']);

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada, DENGAN CATATAN kita menyimpannya dulu setelah yang baru berhasil disalin
            // Namun yang paling aman adalah menyimpan yang baru dulu, baru menghapus yang lama.
            $file = $request->file('logo');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('schools/logos', $filename, 'public');
            
            if ($path) {
                if ($school->logo) {
                    Storage::disk('public')->delete($school->logo);
                }
                $data['logo'] = $path;
            }
        }

        $school->update($data);

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
}
