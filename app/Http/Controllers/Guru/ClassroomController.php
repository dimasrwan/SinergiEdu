<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    private function getTeacherProfile(): Teacher
    {
        return Teacher::where('user_id', auth()->id())->firstOrFail();
    }

    /**
     * Menampilkan daftar kelas yang diajar oleh guru.
     */
    public function index(): View
    {
        $teacher = $this->getTeacherProfile();

        // Cari konteks akademik aktif
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $missingContext = !$activeAcademicYear || !$activeSemester;

        $assignments = collect();

        if (!$missingContext) {
            $assignments = TeacherSubject::with(['classroom', 'subject', 'academicYear', 'semester'])
                ->where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('semester_id', $activeSemester->id)
                ->get();
        }

        return view('pages.guru.classes.index', compact('assignments', 'missingContext', 'activeAcademicYear', 'activeSemester'));
    }

    /**
     * Menampilkan detail penugasan kelas.
     */
    public function show(TeacherSubject $class): View
    {
        $teacher = $this->getTeacherProfile();
        
        // Verifikasi kepemilikan data (Tenant Isolation sudah ter-cover dari Global Scope)
        // Pastikan tugas ini benar-benar milik guru yang sedang login
        abort_if($class->teacher_id !== $teacher->id, 403, 'Anda tidak memiliki akses ke data kelas ini.');

        // Load relasi yang diperlukan untuk detail
        $class->load(['classroom.students', 'subject', 'academicYear', 'semester']);

        return view('pages.guru.classes.show', compact('class'));
    }
}
