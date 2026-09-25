<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ParentRequest;
use App\Models\Role;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ParentController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', \App\Models\StudentParent::class);
        $query = StudentParent::with(['user', 'students.user']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $parents = $query->latest()->paginate(10)->withQueryString();
        $totalParents = StudentParent::count();
        
        return view('pages.admin.parents.index', compact('parents', 'totalParents'));
    }

    public function create(): View
    {
        Gate::authorize('create', \App\Models\StudentParent::class);
        $students = Student::with('user')->whereNull('parent_id')->get();
        return view('pages.admin.parents.create', compact('students'));
    }

    public function store(ParentRequest $request): RedirectResponse
    {
        Gate::authorize('create', \App\Models\StudentParent::class);
        DB::transaction(function () use ($request) {
            $roleOrangTua = Role::where('name', 'orangtua')->firstOrFail();

            // 1. Buat User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $roleOrangTua->id,
            ]);

            // 2. Buat Profil Orang Tua
            $parent = StudentParent::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // 3. Hubungkan Siswa
            if ($request->filled('students')) {
                // Validasi eksplisit ID yang berhak diakses oleh admin saat ini (TenantScope otomatis teraplikasi)
                $validStudentIds = Student::whereIn('id', $request->students)->pluck('id');
                if ($validStudentIds->isNotEmpty()) {
                    Student::whereIn('id', $validStudentIds)->update(['parent_id' => $parent->id]);
                }
            }
        });

        return redirect()->route('admin.parents.index')->with('success', 'Data Orang Tua berhasil ditambahkan.');
    }

    public function show(StudentParent $parent): View
    {
        Gate::authorize('view', $parent);
        $parent->load(['user', 'students.user', 'students.classes']);
        return view('pages.admin.parents.show', compact('parent'));
    }

    public function edit(StudentParent $parent): View
    {
        Gate::authorize('update', $parent);
        $parent->load(['user', 'students']);
        // Tampilkan semua siswa yang tidak memiliki orang tua, PLUS anak-anak orang tua ini sendiri
        $students = Student::with('user')
            ->where(function ($query) use ($parent) {
                $query->whereNull('parent_id')
                      ->orWhere('parent_id', $parent->id);
            })->get();

        $activeStudentIds = $parent->students->pluck('id')->toArray();

        return view('pages.admin.parents.edit', compact('parent', 'students', 'activeStudentIds'));
    }

    public function update(ParentRequest $request, StudentParent $parent): RedirectResponse
    {
        Gate::authorize('update', $parent);
        DB::transaction(function () use ($request, $parent) {
            // 1. Update User
            $user = $parent->user;
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            // 2. Update Profil
            $parent->update([
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // 3. Hubungkan Siswa
            // Hapus relasi sebelumnya
            Student::where('parent_id', $parent->id)->update(['parent_id' => null]);
            // Set relasi baru
            if ($request->filled('students')) {
                // Validasi eksplisit ID yang berhak diakses oleh admin saat ini (TenantScope otomatis teraplikasi)
                $validStudentIds = Student::whereIn('id', $request->students)->pluck('id');
                if ($validStudentIds->isNotEmpty()) {
                    Student::whereIn('id', $validStudentIds)->update(['parent_id' => $parent->id]);
                }
            }
        });

        return redirect()->route('admin.parents.index')->with('success', 'Data Orang Tua berhasil diperbarui.');
    }

    public function destroy(StudentParent $parent): RedirectResponse
    {
        Gate::authorize('delete', $parent);
        DB::transaction(function () use ($parent) {
            $user = $parent->user;
            // Putuskan relasi anak
            Student::where('parent_id', $parent->id)->update(['parent_id' => null]);
            
            $parent->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.parents.index')->with('success', 'Data Orang Tua berhasil dihapus.');
    }
}
