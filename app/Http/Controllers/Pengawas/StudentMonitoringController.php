<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentMonitoringController extends Controller
{
    /**
     * Tampilkan daftar siswa dengan monitoring hasil belajar.
     */
    public function index(): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        if (!$activeYear || !$activeSemester) {
            $students = collect();
            $classes = Classroom::query()
                ->when(auth()->user()->school_id, fn ($q) => $q->where('school_id', auth()->user()->school_id))
                ->get();
            $selectedClassId = request('class_id');
            return view('pages.pengawas.students.index', compact(
                'students', 'classes', 'selectedClassId', 'activeYear', 'activeSemester'
            ));
        }

        // Dapatkan semua kelas di sekolah (hanya kelas dari sekolah user yang login)
        $classes = Classroom::query()
            ->when(auth()->user()->school_id, fn ($q) => $q->where('school_id', auth()->user()->school_id))
            ->get();
        $selectedClassId = request('class_id', $classes->first()?->id);

        // Dapatkan siswa dengan hasil belajar
        $students = Student::query()
            ->when(auth()->user()->school_id, fn ($q) => $q->where('school_id', auth()->user()->school_id))
            ->when($selectedClassId, function ($query) use ($selectedClassId, $activeYear) {
                return $query->whereHas('classes', function ($q) use ($selectedClassId, $activeYear) {
                    $q->where('classes.id', $selectedClassId)
                      ->where('student_classes.academic_year_id', $activeYear->id);
                });
            })
            ->with(['user', 'parent.user', 'studentGrades' => function ($q) use ($activeYear, $activeSemester) {
                $q->where('academic_year_id', $activeYear->id)
                  ->where('semester_id', $activeSemester->id);
            }])
            ->paginate(15);

        return view('pages.pengawas.students.index', compact(
            'students', 'classes', 'selectedClassId', 'activeYear', 'activeSemester'
        ));
    }

    /**
     * Tampilkan detail siswa dengan riwayat hasil belajar.
     */
    public function show(Student $student): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        // Cek akses - hanya pengawas dari sekolah yang sama
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized');
        }

        // Dapatkan kelas aktif siswa
        $activeClassroom = null;
        if ($activeYear) {
            $activeClassroom = $student->classes()
                ->wherePivot('academic_year_id', $activeYear->id)
                ->first();
        }

        // Dapatkan hasil belajar
        $grades = StudentGrade::where('student_id', $student->id)
            ->when($activeYear, fn ($q) => $q->where('academic_year_id', $activeYear->id))
            ->when($activeSemester, fn ($q) => $q->where('semester_id', $activeSemester->id))
            ->with('subject', 'teacher.user')
            ->get();

        // Hitung statistik
        $stats = [
            'avg_pre_test' => $grades->avg('pre_test_score') ?? 0,
            'avg_assignment' => $grades->avg('assignment_score') ?? 0,
            'avg_post_test' => $grades->avg('post_test_score') ?? 0,
            'avg_character' => $grades->avg('character_score') ?? 0,
            'avg_memorization' => $grades->avg('memorization_score') ?? 0,
            'overall_avg' => $grades->avg('average_score') ?? 0,
        ];

        // Dapatkan rata-rata kelas untuk perbandingan
        $classAverage = 0;
        if ($activeYear && $activeSemester) {
            $classAverage = StudentGrade::whereHas('student', function ($q) use ($activeClassroom) {
                $q->whereHas('classes', function ($sq) use ($activeClassroom) {
                    $sq->where('classes.id', $activeClassroom?->id);
                });
            })
                ->where('academic_year_id', $activeYear->id)
                ->where('semester_id', $activeSemester->id)
                ->avg(DB::raw('(COALESCE(pre_test_score, 0) + COALESCE(assignment_score, 0) + COALESCE(post_test_score, 0) + COALESCE(character_score, 0) + COALESCE(memorization_score, 0)) / 5')) ?? 0;
        }

        return view('pages.pengawas.students.show', compact(
            'student', 'grades', 'stats', 'classAverage', 'activeClassroom', 
            'activeYear', 'activeSemester'
        ));
    }

    /**
     * Download hasil belajar dalam format Excel.
     */
    public function downloadReport(): StreamedResponse
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();
        $selectedClassId = request('class_id');

        if (!$activeYear || !$activeSemester) {
            abort(404, 'Tahun ajaran atau semester aktif tidak ditemukan.');
        }

        $students = Student::query()
            ->when(auth()->user()->school_id, fn ($q) => $q->where('school_id', auth()->user()->school_id))
            ->when($selectedClassId, function ($query) use ($selectedClassId, $activeYear) {
                return $query->whereHas('classes', function ($q) use ($selectedClassId, $activeYear) {
                    $q->where('classes.id', $selectedClassId)
                      ->where('student_classes.academic_year_id', $activeYear->id);
                });
            })
            ->with(['user', 'studentGrades' => function ($q) use ($activeYear, $activeSemester) {
                $q->where('academic_year_id', $activeYear->id)
                  ->where('semester_id', $activeSemester->id);
            }])
            ->get();

        $filename = 'hasil_belajar_' . ($activeYear?->name ?? 'tahun') . '.csv';

        $response = new StreamedResponse(function () use ($students) {
            $handle = fopen('php://output', 'w');
            
            // Header
            fputcsv($handle, ['NIS', 'NISN', 'Nama Siswa', 'Tes Awal', 'Tugas', 'Tes Akhir', 'Karakter', 'Hafalan', 'Rata-rata']);

            // Data
            foreach ($students as $student) {
                foreach ($student->studentGrades as $grade) {
                    fputcsv($handle, [
                        $student->nis,
                        $student->nisn,
                        $student->user?->name,
                        $grade->pre_test_score,
                        $grade->assignment_score,
                        $grade->post_test_score,
                        $grade->character_score,
                        $grade->memorization_score,
                        $grade->average_score,
                    ]);
                }
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        return $response;
    }
}
