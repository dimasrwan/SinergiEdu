<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
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
        Gate::authorize('viewAny', \App\Models\StudentClass::class);
        $query = StudentClass::with(['student.user', 'classroom', 'academicYear']);

        // Search based on student name or NIS
        if ($search = $request->input('search')) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where(function($q) use ($search) {
                  $q->where('nis', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
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
        Gate::authorize('create', \App\Models\StudentClass::class);
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        
        if (!$activeAcademicYear) {
            return redirect()->route('admin.student-placements.index')->with('error', 'Tidak ada Tahun Ajaran Aktif. Silakan atur Tahun Ajaran terlebih dahulu.');
        }

        $students = Student::with('user')
            ->whereDoesntHave('classes', function ($query) use ($activeAcademicYear) {
                $query->where('student_classes.academic_year_id', $activeAcademicYear->id);
            })
            ->get()
            ->sortBy(fn($s) => $s->user->name ?? '');

        $classrooms = Classroom::where('academic_year_id', $activeAcademicYear->id)->orderBy('name')->get();
        $academicYears = collect([$activeAcademicYear]); // Only pass the active one for UI readonly

        return view('pages.admin.student-placements.create', compact(
            'students',
            'classrooms',
            'academicYears',
            'activeAcademicYear'
        ));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', \App\Models\StudentClass::class);
        
        $validated = $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'numeric',
            'class_id' => 'required|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ], [
            'student_ids.required' => 'Pilih minimal satu siswa.',
            'class_id.required' => 'Kelas wajib dipilih.',
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
        ]);

        $successCount = 0;
        $failedStudents = [];

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($validated['student_ids'] as $studentId) {
                // Fetch student (applies TenantScope automatically)
                $student = Student::with('user')->find($studentId);
                
                if (!$student) {
                    $failedStudents[] = "ID Siswa {$studentId} tidak ditemukan atau bukan milik sekolah ini.";
                    continue;
                }

                $existingPlacement = StudentClass::with('classroom')
                    ->where('student_id', $student->id)
                    ->where('academic_year_id', $validated['academic_year_id'])
                    ->first();

                if ($existingPlacement) {
                    $failedStudents[] = "{$student->user->name} (Sudah di kelas {$existingPlacement->classroom->name})";
                    continue;
                }

                StudentClass::create([
                    'student_id' => $student->id,
                    'class_id' => $validated['class_id'],
                    'academic_year_id' => $validated['academic_year_id'],
                ]);
                $successCount++;
            }
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan data: ' . $e->getMessage());
        }

        $message = "Berhasil menempatkan {$successCount} siswa.";
        if (count($failedStudents) > 0) {
            $message .= " Gagal menempatkan " . count($failedStudents) . " siswa: " . implode(', ', $failedStudents);
            return redirect()->route('admin.student-placements.index')->with('warning', $message);
        }

        return redirect()->route('admin.student-placements.index')->with('success', $message);
    }

    public function edit(StudentClass $studentPlacement)
    {
        Gate::authorize('update', $studentPlacement);
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
        Gate::authorize('update', $studentPlacement);
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
        Gate::authorize('delete', $studentPlacement);
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
