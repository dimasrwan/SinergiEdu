<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentRequest;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', \App\Models\Student::class);
        $query = Student::with(['user', 'parent.user', 'classes']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%");
                })->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($classId = $request->input('class_id')) {
            $query->whereHas('classes', function ($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        $students = $query->latest()->paginate(10)->withQueryString();
        $totalStudents = Student::count();
        $classes = Classroom::all();
        $academicYears = AcademicYear::orderByDesc('year')->get();

        return view('pages.admin.students.index', compact('students', 'totalStudents', 'classes', 'academicYears'));
    }

    public function create(): View
    {
        Gate::authorize('create', \App\Models\Student::class);
        $classes = Classroom::all();
        $parents = StudentParent::with('user')->get();
        return view('pages.admin.students.create', compact('classes', 'parents'));
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        Gate::authorize('create', \App\Models\Student::class);
        DB::transaction(function () use ($request) {
            $roleSiswa = Role::where('name', 'siswa')->firstOrFail();

            // 1. Buat User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $roleSiswa->id,
            ]);

            // Validasi Parent secara eksplisit
            $parentId = $request->parent_id;
            if ($parentId) {
                $validParent = StudentParent::find($parentId);
                $parentId = $validParent ? $validParent->id : null;
            }

            // 2. Buat Profil Siswa
            $student = Student::create([
                'user_id' => $user->id,
                'parent_id' => $parentId,
                'nis' => $request->nis,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Data Siswa berhasil ditambahkan.');
    }

    public function show(Student $student): View
    {
        Gate::authorize('view', $student);
        $student->load(['user', 'parent.user', 'classes.academicYear']);
        $activeClass = $student->activeClassroom();
        $placements = \App\Models\StudentClass::with(['classroom', 'academicYear'])
            ->where('student_id', $student->id)
            ->orderByDesc('academic_year_id')
            ->get();
            
        $classes = Classroom::all();
        $academicYears = AcademicYear::orderByDesc('year')->get();
        
        return view('pages.admin.students.show', compact('student', 'activeClass', 'placements', 'classes', 'academicYears'));
    }

    public function edit(Student $student): View
    {
        Gate::authorize('update', $student);
        $student->load(['user', 'classes']);
        $classes = Classroom::all();
        $parents = StudentParent::with('user')->get();

        // Dapatkan kelas aktif saat ini
        $activeClass = $student->activeClassroom();
        $activeClassId = $activeClass ? $activeClass->id : null;

        return view('pages.admin.students.edit', compact('student', 'classes', 'parents', 'activeClassId'));
    }

    public function update(StudentRequest $request, Student $student): RedirectResponse
    {
        Gate::authorize('update', $student);
        DB::transaction(function () use ($request, $student) {
            // 1. Update User
            $user = $student->user;
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            // Validasi Parent secara eksplisit
            $parentId = $request->parent_id;
            if ($parentId) {
                $validParent = StudentParent::find($parentId);
                $parentId = $validParent ? $validParent->id : null;
            }

            // 2. Update Profil Siswa
            $student->update([
                'parent_id' => $parentId,
                'nis' => $request->nis,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Data Siswa berhasil diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        Gate::authorize('delete', $student);
        DB::transaction(function () use ($student) {
            $user = $student->user;
            $student->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.students.index')->with('success', 'Data Siswa berhasil dihapus.');
    }
}
