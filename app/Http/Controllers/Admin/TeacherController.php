<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeacherRequest;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', \App\Models\Teacher::class);
        $search = request('search');
        
        $teachers = Teacher::with(['user', 'classes', 'subjects'])
            ->when($search, function ($query) use ($search) {
                $query->where(function($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('nip', 'like', "%{$search}%");
            });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
            
        $subjects = \App\Models\Subject::orderBy('name')->get();
        $classrooms = \App\Models\Classroom::orderBy('name')->get();
        $academicYears = \App\Models\AcademicYear::orderByDesc('year')->get();
        $semesters = \App\Models\Semester::with('academicYear')
            ->get()
            ->sortByDesc(fn($s) => $s->academicYear->year . ' ' . $s->name);
            
        return view('pages.admin.teachers.index', compact('teachers', 'subjects', 'classrooms', 'academicYears', 'semesters'));
    }

    public function create(): View
    {
        Gate::authorize('create', \App\Models\Teacher::class);
        $classes = Classroom::all();
        $subjects = Subject::all();
        return view('pages.admin.teachers.create', compact('classes', 'subjects'));
    }

    public function store(TeacherRequest $request): RedirectResponse
    {
        Gate::authorize('create', \App\Models\Teacher::class);
        DB::transaction(function () use ($request) {
            // Dapatkan Role ID Guru
            $roleGuru = Role::where('name', 'guru')->firstOrFail();

            // 1. Buat User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $roleGuru->id,
            ]);

            // 2. Buat Profil Guru
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

        });

        return redirect()->route('admin.teachers.index')->with('success', 'Data Guru berhasil ditambahkan.');
    }

    public function show(Teacher $teacher): View
    {
        Gate::authorize('view', $teacher);
        $teacher->load(['user', 'classes', 'subjects']);
        $assignments = \App\Models\TeacherSubject::with(['subject', 'classroom', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->orderByDesc('academic_year_id')
            ->orderBy('semester_id')
            ->get();
            
        $subjects = \App\Models\Subject::orderBy('name')->get();
        $classrooms = \App\Models\Classroom::orderBy('name')->get();
        $academicYears = \App\Models\AcademicYear::orderByDesc('year')->get();
        $semesters = \App\Models\Semester::with('academicYear')
            ->get()
            ->sortByDesc(fn($s) => $s->academicYear->year . ' ' . $s->name);
            
        return view('pages.admin.teachers.show', compact('teacher', 'assignments', 'subjects', 'classrooms', 'academicYears', 'semesters'));
    }

    public function edit(Teacher $teacher): View
    {
        Gate::authorize('update', $teacher);
        $teacher->load(['user', 'classes', 'subjects']);
        return view('pages.admin.teachers.edit', compact('teacher'));
    }

    public function update(TeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        Gate::authorize('update', $teacher);
        DB::transaction(function () use ($request, $teacher) {
            // 1. Update User
            $user = $teacher->user;
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            // 2. Update Profil Guru
            $teacher->update([
                'nip' => $request->nip,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

        });

        return redirect()->route('admin.teachers.index')->with('success', 'Data Guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        Gate::authorize('delete', $teacher);
        DB::transaction(function () use ($teacher) {
            // Hapus user yang secara otomatis melakukan cascade delete profil (jika diset constrained di migration)
            // Tapi untuk amannya, hapus keduanya secara berurutan dalam transaksi
            $user = $teacher->user;
            $teacher->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Data Guru berhasil dihapus.');
    }
}
