<?php

declare(strict_types=1);

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\StudentGrade;
use App\Models\Teacher;
use App\Services\KepalaSekolah\SupervisionService;
use Illuminate\View\View;

class SupervisionController extends Controller
{
    public function penilaianStatus(SupervisionService $supervision): View
    {
        $gradingStatus = $supervision->getGradingStatus();

        $completed = $gradingStatus->where('status', 'completed')->count();
        $pending = $gradingStatus->where('status', 'pending')->count();
        $total = $gradingStatus->count();

        return view('pages.kepala-sekolah.supervisi.penilaian', compact(
            'gradingStatus', 'completed', 'pending', 'total'
        ));
    }

    public function laporanGuru(SupervisionService $supervision): View
    {
        $teacherReports = $supervision->getTeacherReports();

        $topTeachers = $teacherReports->take(5);

        return view('pages.kepala-sekolah.supervisi.laporan-guru', compact('teacherReports', 'topTeachers'));
    }

    public function teacherDetail(Teacher $teacher): View
    {
        $teacher->load(['user', 'subjects', 'classes']);

        $assignments = $teacher->subjects()->get();
        $grades = StudentGrade::where('teacher_id', $teacher->id)->get();

        $classRows = $teacher->classes()->distinct()->get()->map(function ($class) use ($teacher, $grades) {
            $classGrades = $grades->where('class_id', $class->id);
            return (object) [
                'class_name' => $class->name,
                'avg' => $classGrades->isNotEmpty() ? round($classGrades->avg(fn ($g) => $g->average_score) ?? 0, 2) : 0,
                'avg_character' => round($classGrades->avg('character_score') ?? 0, 1),
                'avg_memorization' => round($classGrades->avg('memorization_score') ?? 0, 1),
                'student_count' => $class->students()->count(),
            ];
        });

        return view('pages.kepala-sekolah.supervisi.teacher-detail', compact('teacher', 'classRows'));
    }
}
