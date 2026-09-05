<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherAssignmentController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', \App\Models\TeacherSubject::class);
        $query = TeacherSubject::with(['teacher.user', 'subject', 'classroom', 'academicYear', 'semester']);

        // Search based on teacher user name, subject name, or class name
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where(function($q) use ($search) {
                  $q->whereHas('teacher.user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('subject', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('classroom', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
              });
            });
        }

        // Filters
        if ($academicYearId = $request->input('academic_year_id')) {
            $query->where('academic_year_id', $academicYearId);
        }
        if ($semesterId = $request->input('semester_id')) {
            $query->where('semester_id', $semesterId);
        }
        if ($teacherId = $request->input('teacher_id')) {
            $query->where('teacher_id', $teacherId);
        }
        if ($subjectId = $request->input('subject_id')) {
            $query->where('subject_id', $subjectId);
        }
        if ($classId = $request->input('class_id')) {
            $query->where('class_id', $classId);
        }

        $assignments = $query->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $teachers = Teacher::with('user')->get()->sortBy('user.name');
        $subjects = Subject::orderBy('name')->get();
        $classrooms = Classroom::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('year')->get();
        $semesters = Semester::with('academicYear')
            ->get()
            ->sortByDesc(fn($s) => $s->academicYear->year . ' ' . $s->name);

        return view('pages.admin.teacher-assignments.index', compact(
            'assignments',
            'teachers',
            'subjects',
            'classrooms',
            'academicYears',
            'semesters'
        ));
    }

    public function create()
    {
        Gate::authorize('create', \App\Models\TeacherSubject::class);
        $teachers = Teacher::with('user')->get()->sortBy(fn($t) => $t->user->name ?? '');
        $subjects = Subject::orderBy('name')->get();
        
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        if (!$activeAcademicYear) {
            return redirect()->route('admin.teacher-assignments.index')->with('error', 'Tidak ada Tahun Ajaran Aktif. Silakan atur Tahun Ajaran terlebih dahulu.');
        }

        $activeSemester = Semester::where('academic_year_id', $activeAcademicYear->id)
                                  ->where('is_active', true)
                                  ->first();
                                  
        if (!$activeSemester) {
            return redirect()->route('admin.teacher-assignments.index')->with('error', 'Tidak ada Semester Aktif. Silakan atur Semester terlebih dahulu.');
        }

        $classrooms = Classroom::where('academic_year_id', $activeAcademicYear->id)->orderBy('name')->get();
        
        $academicYears = collect([$activeAcademicYear]);
        $semesters = collect([$activeSemester]);

        return view('pages.admin.teacher-assignments.create', compact(
            'teachers',
            'subjects',
            'classrooms',
            'academicYears',
            'semesters',
            'activeAcademicYear',
            'activeSemester'
        ));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', \App\Models\TeacherSubject::class);
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_id' => 'required|exists:semesters,id',
            'assignments' => 'required|array|min:1',
            'assignments.*.class_id' => 'required|numeric',
            'assignments.*.subject_id' => 'required|numeric',
        ], [
            'teacher_id.required' => 'Guru wajib dipilih.',
            'assignments.required' => 'Minimal satu penugasan wajib ditambahkan.',
            'assignments.*.class_id.required' => 'Kelas wajib dipilih.',
            'assignments.*.subject_id.required' => 'Mata pelajaran wajib dipilih.',
        ]);

        $successCount = 0;
        $failedAssignments = [];

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $teacherId = $validated['teacher_id'];
            $academicYearId = $validated['academic_year_id'];
            $semesterId = $validated['semester_id'];

            foreach ($validated['assignments'] as $index => $assignment) {
                $classId = $assignment['class_id'];
                $subjectId = $assignment['subject_id'];
                
                $classroom = Classroom::find($classId);
                $subject = Subject::find($subjectId);
                
                if (!$classroom || !$subject) {
                    $failedAssignments[] = "Baris " . ($index + 1) . ": Kelas atau Mapel tidak ditemukan di database.";
                    continue;
                }

                $exists = TeacherSubject::where('teacher_id', $teacherId)
                    ->where('subject_id', $subjectId)
                    ->where('class_id', $classId)
                    ->where('academic_year_id', $academicYearId)
                    ->where('semester_id', $semesterId)
                    ->exists();

                if ($exists) {
                    $failedAssignments[] = "Baris " . ($index + 1) . ": Penugasan {$subject->name} untuk {$classroom->name} sudah ada.";
                    continue;
                }

                TeacherSubject::create([
                    'teacher_id' => $teacherId,
                    'subject_id' => $subjectId,
                    'class_id' => $classId,
                    'academic_year_id' => $academicYearId,
                    'semester_id' => $semesterId,
                ]);
                
                $successCount++;
            }
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan data: ' . $e->getMessage());
        }

        $message = "Berhasil menambahkan {$successCount} penugasan.";
        if (count($failedAssignments) > 0) {
            $message .= " Gagal menambahkan " . count($failedAssignments) . " penugasan: " . implode(', ', $failedAssignments);
            return redirect()->route('admin.teacher-assignments.index')->with('warning', $message);
        }

        if ($request->input('redirect_to') === 'teacher') {
            return redirect()->route('admin.teachers.show', $teacherId)
                ->with('success', $message);
        }

        return redirect()->route('admin.teacher-assignments.index')
            ->with('success', $message);
    }

    public function edit(TeacherSubject $teacherAssignment)
    {
        Gate::authorize('update', $teacherAssignment);
        $teachers = Teacher::with('user')->get()->sortBy('user.name');
        $subjects = Subject::orderBy('name')->get();
        $classrooms = Classroom::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('year')->get();
        $semesters = Semester::with('academicYear')
            ->get()
            ->sortByDesc(fn($s) => $s->academicYear->year . ' ' . $s->name);

        return view('pages.admin.teacher-assignments.edit', compact(
            'teacherAssignment',
            'teachers',
            'subjects',
            'classrooms',
            'academicYears',
            'semesters'
        ));
    }

    public function update(Request $request, TeacherSubject $teacherAssignment)
    {
        Gate::authorize('update', $teacherAssignment);
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_id' => 'required|exists:semesters,id',
        ], [
            'teacher_id.required' => 'Guru wajib dipilih.',
            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'class_id.required' => 'Kelas wajib dipilih.',
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
            'semester_id.required' => 'Semester wajib dipilih.',
        ]);

        // Duplicate assignment validation (excluding current record)
        $exists = TeacherSubject::where('teacher_id', $validated['teacher_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('class_id', $validated['class_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('semester_id', $validated['semester_id'])
            ->where('id', '!=', $teacherAssignment->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'duplicate' => 'Penugasan guru dengan kombinasi tersebut sudah tersedia.'
            ]);
        }

        $teacherAssignment->update($validated);

        if ($request->input('redirect_to') === 'teacher') {
            return redirect()->route('admin.teachers.show', $validated['teacher_id'])
                ->with('success', 'Penugasan guru berhasil diperbarui.');
        }

        return redirect()->route('admin.teacher-assignments.index')
            ->with('success', 'Penugasan guru berhasil diperbarui.');
    }

    public function destroy(TeacherSubject $teacherAssignment)
    {
        Gate::authorize('delete', $teacherAssignment);
        // Dependency checks (Materials, Assignments, etc.)
        // Since the prompt instructs to check if it's used by materials/assignments,
        // let's check the database if those tables reference teacher_id, subject_id, class_id, etc.
        // Assuming there isn't a direct teacher_subject_id foreign key, we check contextual dependencies.
        // E.g., if a material exists for this teacher, subject, class, and semester.
        
        $hasMaterials = \DB::table('materials')
            ->where('teacher_id', $teacherAssignment->teacher_id)
            ->where('subject_id', $teacherAssignment->subject_id)
            ->where('class_id', $teacherAssignment->class_id)
            ->where('semester_id', $teacherAssignment->semester_id)
            ->exists();

        $hasAssignments = \DB::table('assignments')
            ->where('teacher_id', $teacherAssignment->teacher_id)
            ->where('subject_id', $teacherAssignment->subject_id)
            ->where('class_id', $teacherAssignment->class_id)
            ->where('semester_id', $teacherAssignment->semester_id)
            ->exists();

        if ($hasMaterials || $hasAssignments) {
            return back()->with('error', 'Penugasan ini masih digunakan oleh data pembelajaran (materi/tugas) dan tidak dapat dihapus.');
        }

        $teacherId = $teacherAssignment->teacher_id;
        $teacherAssignment->delete();

        if (request()->input('redirect_to') === 'teacher') {
            return redirect()->route('admin.teachers.show', $teacherId)
                ->with('success', 'Penugasan guru berhasil dihapus.');
        }

        return back()->with('success', 'Penugasan guru berhasil dihapus.');
    }
}
