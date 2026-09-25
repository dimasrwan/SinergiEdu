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

        $activeSchoolId = session('pengawas_school_id');

        if (!$activeYear || !$activeSemester || !$activeSchoolId) {
            $students = collect();
            $classes = Classroom::query()
                ->when($activeSchoolId, fn ($q) => $q->where('school_id', $activeSchoolId))
                ->get();
            $selectedClassId = 'all';
            return view('pages.pengawas.students.index', compact(
                'students', 'classes', 'selectedClassId', 'activeYear', 'activeSemester'
            ));
        }

        // Dapatkan semua kelas di sekolah (hanya kelas dari sekolah aktif)
        $classes = Classroom::query()
            ->when($activeSchoolId, fn ($q) => $q->where('school_id', $activeSchoolId))
            ->get();

        $requestClassId = request('class_id');
        $selectedClassId = ($requestClassId && $requestClassId !== 'all') ? $requestClassId : 'all';

        // Validasi jika class_id spesifik diberikan, pastikan kelas tersebut milik sekolah aktif
        if ($selectedClassId !== 'all') {
            $classExistsInActiveSchool = $classes->contains('id', (int) $selectedClassId);
            if (!$classExistsInActiveSchool) {
                // Jika mencoba akses kelas luar tenant, paksa ke 'all' atau kosongkan
                $selectedClassId = 'all';
            }
        }

        // Dapatkan siswa dengan hasil belajar
        $students = Student::query()
            ->when($activeSchoolId, fn ($q) => $q->where('school_id', $activeSchoolId))
            ->when($selectedClassId !== 'all', function ($query) use ($selectedClassId, $activeYear) {
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
    public function show($studentId): View
    {
        $student = Student::withoutGlobalScopes()->findOrFail($studentId);
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();
        $activeSchoolId = session('pengawas_school_id');

        // Cek akses - hanya siswa dari sekolah pengawasan aktif
        if (!$activeSchoolId || $student->school_id !== (int) $activeSchoolId) {
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
        $requestClassId = request('class_id');
        $selectedClassId = ($requestClassId && $requestClassId !== 'all') ? $requestClassId : 'all';

        $activeSchoolId = session('pengawas_school_id');

        if (!$activeYear || !$activeSemester || !$activeSchoolId) {
            abort(404, 'Tahun ajaran, semester aktif, atau sekolah pengawasan tidak ditemukan.');
        }

        // Validasi jika class_id spesifik diberikan, pastikan kelas milik sekolah aktif
        if ($selectedClassId !== 'all') {
            $classExistsInActiveSchool = Classroom::where('school_id', $activeSchoolId)->where('id', $selectedClassId)->exists();
            if (!$classExistsInActiveSchool) {
                $selectedClassId = 'all';
            }
        }

        $students = Student::query()
            ->when($activeSchoolId, fn ($q) => $q->where('school_id', $activeSchoolId))
            ->when($selectedClassId !== 'all', function ($query) use ($selectedClassId, $activeYear) {
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
