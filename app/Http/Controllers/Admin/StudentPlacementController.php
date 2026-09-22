<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentClass;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        if ($request->has('student_id') && (!$request->has('student_ids') || empty($request->input('student_ids')))) {
            $request->merge([
                'student_ids' => [$request->input('student_id')]
            ]);
        }

        $schoolId = app(TenantService::class)->getSchoolId() ?? auth()->user()->school_id;
        
        $validated = $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => [
                'required',
                'numeric',
                Rule::exists('students', 'id')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'class_id' => [
                'required',
                Rule::exists('classes', 'id')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'academic_year_id' => [
                'required',
                Rule::exists('academic_years', 'id')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
        ], [
            'student_ids.required' => 'Pilih minimal satu siswa.',
            'student_ids.min' => 'Pilih minimal satu siswa.',
            'student_ids.*.exists' => 'Siswa yang dipilih tidak valid atau bukan milik sekolah Anda.',
            'class_id.required' => 'Kelas wajib dipilih.',
            'class_id.exists' => 'Kelas yang dipilih tidak valid atau bukan milik sekolah Anda.',
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
            'academic_year_id.exists' => 'Tahun ajaran yang dipilih tidak valid atau bukan milik sekolah Anda.',
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

            if ($request->input('redirect_to') === 'students_index') {
                return redirect()->route('admin.students.index')->with('warning', $message);
            }
            if ($request->input('redirect_to') === 'student' && $request->input('student_id')) {
                return redirect()->route('admin.students.show', $request->input('student_id'))->with('warning', $message);
            }
            return redirect()->route('admin.student-placements.index')->with('warning', $message);
        }

        if ($request->input('redirect_to') === 'students_index') {
            return redirect()->route('admin.students.index')->with('success', $message);
        }
        if ($request->input('redirect_to') === 'student' && $request->input('student_id')) {
            return redirect()->route('admin.students.show', $request->input('student_id'))->with('success', $message);
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
        $schoolId = app(TenantService::class)->getSchoolId() ?? auth()->user()->school_id;

        $validated = $request->validate([
            'student_id' => [
                'required',
                Rule::exists('students', 'id')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'class_id' => [
                'required',
                Rule::exists('classes', 'id')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'academic_year_id' => [
                'required',
                Rule::exists('academic_years', 'id')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
        ], [
            'student_id.required' => 'Siswa wajib dipilih.',
            'student_id.exists' => 'Siswa yang dipilih tidak valid atau bukan milik sekolah Anda.',
            'class_id.required' => 'Kelas wajib dipilih.',
            'class_id.exists' => 'Kelas yang dipilih tidak valid atau bukan milik sekolah Anda.',
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
            'academic_year_id.exists' => 'Tahun ajaran yang dipilih tidak valid atau bukan milik sekolah Anda.',
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
