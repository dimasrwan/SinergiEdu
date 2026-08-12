<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Classroom::with(['academicYear', 'homeroomTeacher.user'])
                          ->withCount('students');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        $classes = $query->orderBy('name')->paginate(10)->withQueryString();
        
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();

        return view('pages.admin.classes.index', compact('classes', 'academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $teachers = Teacher::with('user')->get()->sortBy(fn($t) => $t->user->name ?? '');

        return view('pages.admin.classes.create', compact('academicYears', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('classes', 'name')->where(function ($query) use ($request) {
                    return $query->where('academic_year_id', $request->academic_year_id);
                })
            ],
            'grade_level' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($request) {
                    if (!str_starts_with(strtoupper($request->name), strtoupper($value))) {
                        $fail('Tingkat kelas (' . $value . ') tidak sesuai dengan awalan nama kelas (' . $request->name . ').');
                    }
                }
            ],
            'academic_year_id' => 'required|exists:academic_years,id',
            'homeroom_teacher_id' => [
                'nullable',
                'exists:teachers,id',
                Rule::unique('classes', 'homeroom_teacher_id')->where(function ($query) use ($request) {
                    return $query->where('academic_year_id', $request->academic_year_id);
                })
            ],
        ], [
            'name.unique' => 'Kelas dengan nama ini sudah ada pada tahun ajaran yang dipilih.',
            'homeroom_teacher_id.unique' => 'Guru ini sudah menjadi wali kelas di kelas lain pada tahun ajaran yang sama.',
        ]);

        Classroom::create($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $class)
    {
        $class->load(['academicYear', 'homeroomTeacher.user', 'students.user']);
        
        // Memuat mapel dan guru yang mengajar (jika relationship ada)
        // Saat ini belum ada relationship bawaan dari Classroom ke (Teacher + Subject) secara langsung selain via student_grades atau pivot. 
        // Pivot teacher_subjects memiliki 'class_id', 'teacher_id', 'subject_id'.
        // Kita bisa kueri DB secara langsung untuk pivot tersebut.
        $teacherSubjects = \DB::table('teacher_subjects')
            ->join('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->join('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->where('teacher_subjects.class_id', $class->id)
            ->select('users.name as teacher_name', 'subjects.name as subject_name')
            ->get();

        return view('pages.admin.classes.show', compact('class', 'teacherSubjects'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $class)
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $teachers = Teacher::with('user')->get()->sortBy(fn($t) => $t->user->name ?? '');

        return view('pages.admin.classes.edit', compact('class', 'academicYears', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $class)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('classes', 'name')->where(function ($query) use ($request) {
                    return $query->where('academic_year_id', $request->academic_year_id);
                })->ignore($class->id)
            ],
            'grade_level' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($request) {
                    if (!str_starts_with(strtoupper($request->name), strtoupper($value))) {
                        $fail('Tingkat kelas (' . $value . ') tidak sesuai dengan awalan nama kelas (' . $request->name . ').');
                    }
                }
            ],
            'academic_year_id' => 'required|exists:academic_years,id',
            'homeroom_teacher_id' => [
                'nullable',
                'exists:teachers,id',
                Rule::unique('classes', 'homeroom_teacher_id')->where(function ($query) use ($request) {
                    return $query->where('academic_year_id', $request->academic_year_id);
                })->ignore($class->id)
            ],
        ], [
            'name.unique' => 'Kelas dengan nama ini sudah ada pada tahun ajaran yang dipilih.',
            'homeroom_teacher_id.unique' => 'Guru ini sudah menjadi wali kelas di kelas lain pada tahun ajaran yang sama.',
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $class)
    {
        if ($class->students()->count() > 0) {
            return redirect()->route('admin.classes.index')->with('error', 'Kelas tidak dapat dihapus karena masih memiliki ' . $class->students()->count() . ' siswa. Pindahkan atau keluarkan siswa terlebih dahulu.');
        }

        $class->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}
