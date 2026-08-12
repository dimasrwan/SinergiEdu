<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Http\Request;

class StudentPlacementController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentClass::with(['student.user', 'classroom', 'academicYear']);

        // Search based on student name or NIS
        if ($search = $request->input('search')) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filters
        if ($academicYearId = $request->input('academic_year_id')) {
            $query->where('academic_year_id', $academicYearId);
        }
        if ($classId = $request->input('class_id')) {
            $query->where('class_id', $classId);
        }

        $placements = $query->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $academicYears = AcademicYear::orderByDesc('year')->get();
        $classrooms = Classroom::orderBy('name')->get();

        return view('pages.admin.student-placements.index', compact(
            'placements',
            'academicYears',
            'classrooms'
        ));
    }

    public function create()
    {
        $students = Student::with('user')->get()->sortBy('user.name');
        $classrooms = Classroom::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('year')->get();

        return view('pages.admin.student-placements.create', compact(
            'students',
            'classrooms',
            'academicYears'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ], [
            'student_id.required' => 'Siswa wajib dipilih.',
            'class_id.required' => 'Kelas wajib dipilih.',
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
        ]);

        // Duplicate assignment validation: One student can only have one class per academic year
        $existingPlacement = StudentClass::with('classroom')
            ->where('student_id', $validated['student_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->first();

        if ($existingPlacement) {
            return back()->withInput()->withErrors([
                'duplicate' => "Siswa sudah ditempatkan pada kelas {$existingPlacement->classroom->name} untuk tahun ajaran tersebut."
            ]);
        }

        StudentClass::create($validated);

        if ($request->input('redirect_to') === 'student') {
            return redirect()->route('admin.students.show', $validated['student_id'])
                ->with('success', 'Penempatan siswa berhasil ditambahkan.');
        }

        return redirect()->route('admin.student-placements.index')
            ->with('success', 'Penempatan siswa berhasil ditambahkan.');
    }

    public function edit(StudentClass $studentPlacement)
    {
        $students = Student::with('user')->get()->sortBy('user.name');
        $classrooms = Classroom::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('year')->get();

        return view('pages.admin.student-placements.edit', compact(
            'studentPlacement',
            'students',
            'classrooms',
            'academicYears'
        ));
    }

    public function update(Request $request, StudentClass $studentPlacement)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ], [
            'student_id.required' => 'Siswa wajib dipilih.',
            'class_id.required' => 'Kelas wajib dipilih.',
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
        ]);

        // Duplicate validation (excluding current record)
        $existingPlacement = StudentClass::with('classroom')
            ->where('student_id', $validated['student_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('id', '!=', $studentPlacement->id)
            ->first();

        if ($existingPlacement) {
            return back()->withInput()->withErrors([
                'duplicate' => "Siswa sudah ditempatkan pada kelas {$existingPlacement->classroom->name} untuk tahun ajaran tersebut."
            ]);
        }

        $studentPlacement->update($validated);

        if ($request->input('redirect_to') === 'student') {
            return redirect()->route('admin.students.show', $validated['student_id'])
                ->with('success', 'Penempatan siswa berhasil diperbarui (Pindah Kelas).');
        }

        return redirect()->route('admin.student-placements.index')
            ->with('success', 'Penempatan siswa berhasil diperbarui (Pindah Kelas).');
    }

    public function destroy(StudentClass $studentPlacement)
    {
        // Only deletes the relationship, not the student or class.
        $studentId = $studentPlacement->student_id;
        $studentPlacement->delete();

        if (request()->input('redirect_to') === 'student') {
            return redirect()->route('admin.students.show', $studentId)
                ->with('success', 'Penempatan siswa berhasil dihapus.');
        }

        return back()->with('success', 'Penempatan siswa berhasil dihapus.');
    }
}
