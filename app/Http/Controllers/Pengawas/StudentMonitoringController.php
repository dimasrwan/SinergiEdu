<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Feedback;
use App\Models\PengawasFeedback;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentMonitoringController extends Controller
{
    /**
     * Daftar semua siswa di sekolah dengan filter kelas.
     */
    public function index(Request $request): View
    {
        $classrooms = Classroom::orderBy('name')->get();
        $activeYear  = AcademicYear::where('is_active', true)->first();

        $query = Student::with(['user', 'parent.user', 'classes']);

        if ($request->filled('class_id')) {
            $classId = $request->class_id;
            $query->whereHas('classes', fn($q) => $q->where('class_id', $classId));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
        }

        $students = $query->orderBy('id')->paginate(20)->withQueryString();

        return view('pages.pengawas.students.index', compact('students', 'classrooms', 'activeYear'));
    }

    /**
     * Detail hasil belajar seorang siswa.
     */
    public function show(Student $student): View
    {
        $student->load(['user', 'parent.user', 'classes', 'grades.subject', 'grades.classroom', 'grades.semester', 'grades.academicYear']);

        $activeYear     = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        // Nilai per komponen semua semester
        $grades = StudentGrade::where('student_id', $student->id)
            ->with(['subject', 'classroom', 'semester', 'academicYear', 'teacher.user'])
            ->orderBy('academic_year_id')
            ->orderBy('semester_id')
            ->get();

        // Nilai semester aktif
        $activeGrades = $grades->when(
            $activeYear && $activeSemester,
            fn($col) => $col->where('academic_year_id', $activeYear?->id)
                             ->where('semester_id', $activeSemester?->id)
        );

        // Data untuk grafik perkembangan (average per periode)
        $chartData = $grades->groupBy(fn($g) => ($g->academicYear?->name ?? '-') . ' ' . ($g->semester?->name ?? ''))->map(function ($group) {
            return [
                'label'        => $group->first()->academicYear?->name . ' - Sem. ' . $group->first()->semester?->name,
                'pre_test'     => round($group->whereNotNull('pre_test_score')->avg('pre_test_score') ?? 0, 1),
                'post_test'    => round($group->whereNotNull('post_test_score')->avg('post_test_score') ?? 0, 1),
                'assignment'   => round($group->whereNotNull('assignment_score')->avg('assignment_score') ?? 0, 1),
                'character'    => round($group->whereNotNull('character_score')->avg('character_score') ?? 0, 1),
                'memorization' => round($group->whereNotNull('memorization_score')->avg('memorization_score') ?? 0, 1),
            ];
        })->values();

        // Feedback dari Guru
        $teacherFeedbacks = Feedback::where('student_id', $student->id)
            ->with('teacher.user', 'subject')
            ->latest()
            ->take(10)
            ->get();

        // Feedback dari Pengawas untuk siswa ini
        $pengawasFeedbacks = PengawasFeedback::where('student_id', $student->id)
            ->with('pengawas', 'classroom')
            ->latest()
            ->get();

        // Kelas aktif siswa
        $activeClassroom = $student->activeClassroom();

        return view('pages.pengawas.students.show', compact(
            'student', 'grades', 'activeGrades', 'chartData',
            'teacherFeedbacks', 'pengawasFeedbacks', 'activeClassroom',
            'activeYear', 'activeSemester'
        ));
    }
}
