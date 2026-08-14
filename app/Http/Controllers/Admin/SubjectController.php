<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', \App\Models\Subject::class);
        $query = Subject::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
              });
        }

        $subjects = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('pages.admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', \App\Models\Subject::class);
        return view('pages.admin.subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', \App\Models\Subject::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
            'code' => 'required|string|max:50|unique:subjects,code',
        ], [
            'name.unique' => 'Nama mata pelajaran ini sudah terdaftar.',
            'code.unique' => 'Kode mata pelajaran ini sudah terdaftar.',
        ]);

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        Gate::authorize('view', $subject);
        // Memuat pivot guru dan kelas
        $teacherSubjects = \DB::table('teacher_subjects')
            ->join('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->join('classes', 'teacher_subjects.class_id', '=', 'classes.id')
            ->where('teacher_subjects.subject_id', $subject->id)
            ->select('users.name as teacher_name', 'classes.name as class_name')
            ->get();

        // Mengelompokkan guru dengan kelas-kelasnya
        $groupedTeachers = [];
        foreach ($teacherSubjects as $ts) {
            $groupedTeachers[$ts->teacher_name][] = $ts->class_name;
        }

        return view('pages.admin.subjects.show', compact('subject', 'groupedTeachers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        Gate::authorize('update', $subject);
        return view('pages.admin.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        Gate::authorize('update', $subject);
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'code' => 'required|string|max:50|unique:subjects,code,' . $subject->id,
        ], [
            'name.unique' => 'Nama mata pelajaran ini sudah terdaftar.',
            'code.unique' => 'Kode mata pelajaran ini sudah terdaftar.',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        Gate::authorize('delete', $subject);
        // Proteksi Hapus: Cek dependensi
        $dependenciesCount = 0;
        $dependenciesCount += \DB::table('teacher_subjects')->where('subject_id', $subject->id)->count();
        $dependenciesCount += \DB::table('student_grades')->where('subject_id', $subject->id)->count();
        $dependenciesCount += \DB::table('materials')->where('subject_id', $subject->id)->count();
        $dependenciesCount += \DB::table('assignments')->where('subject_id', $subject->id)->count();
        $dependenciesCount += \DB::table('feedbacks')->where('subject_id', $subject->id)->count();

        if ($dependenciesCount > 0) {
            return redirect()->route('admin.subjects.index')->with('error', 'Mata pelajaran ini masih digunakan oleh ' . $dependenciesCount . ' data operasional (penugasan, materi, atau nilai). Anda tidak dapat menghapusnya.');
        }

        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
