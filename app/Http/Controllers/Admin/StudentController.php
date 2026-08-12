<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

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

        return view('pages.admin.students.index', compact('students', 'totalStudents', 'classes'));
    }

    public function create(): View
    {
        $classes = Classroom::all();
        $parents = StudentParent::with('user')->get();
        return view('pages.admin.students.create', compact('classes', 'parents'));
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $roleSiswa = Role::where('name', 'siswa')->firstOrFail();

            // 1. Buat User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $roleSiswa->id,
            ]);

            // 2. Buat Profil Siswa
            $student = Student::create([
                'user_id' => $user->id,
                'parent_id' => $request->parent_id,
                'nis' => $request->nis,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
            ]);

            // 3. Tambahkan ke Kelas Aktif (Tahun Ajaran aktif)
            $activeYear = AcademicYear::where('is_active', true)->first();
            if ($activeYear) {
                DB::table('student_classes')->insert([
                    'student_id' => $student->id,
                    'class_id' => (int) $request->class_id,
                    'academic_year_id' => $activeYear->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return redirect()->route('admin.students.index')->with('success', 'Data Siswa berhasil ditambahkan.');
    }

    public function show(Student $student): View
    {
        $student->load(['user', 'parent.user', 'classes.academicYear']);
        $activeClass = $student->activeClassroom();
        $placements = \App\Models\StudentClass::with(['classroom', 'academicYear'])
            ->where('student_id', $student->id)
            ->orderByDesc('academic_year_id')
            ->get();
        
        return view('pages.admin.students.show', compact('student', 'activeClass', 'placements'));
    }

    public function edit(Student $student): View
    {
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

            // 2. Update Profil Siswa
            $student->update([
                'parent_id' => $request->parent_id,
                'nis' => $request->nis,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
            ]);

            // 3. Update/Sinkron Kelas Aktif (Tahun Ajaran aktif)
            $activeYear = AcademicYear::where('is_active', true)->first();
            if ($activeYear) {
                DB::table('student_classes')
                    ->updateOrInsert(
                        ['student_id' => $student->id, 'academic_year_id' => $activeYear->id],
                        ['class_id' => (int) $request->class_id, 'updated_at' => now()]
                    );
            }
        });

        return redirect()->route('admin.students.index')->with('success', 'Data Siswa berhasil diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
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
