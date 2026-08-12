<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard Pengawas dengan statistik monitoring seluruh sekolah.
     */
    public function index(): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        // Penghitungan Data Sekolah
        $totalTeachers = Teacher::count();
        $totalStudents = Student::count();
        $totalClasses = Classroom::count();
        $totalSubjects = Subject::count();

        // Agregat Nilai Sekolah
        $schoolAvgGrade = 0;
        $avgPreTest = 0;
        $avgAssignment = 0;
        $avgPostTest = 0;
        $avgCharacter = 0;
        $avgMemorization = 0;

        if ($activeYear && $activeSemester) {
            $grades = StudentGrade::where('academic_year_id', $activeYear->id)
                ->where('semester_id', $activeSemester->id)
                ->get();

            if ($grades->isNotEmpty()) {
                $schoolAvgGrade = round($grades->avg(function ($g) { return $g->average_score; }) ?? 0, 2);
                $avgPreTest = round($grades->whereNotNull('pre_test_score')->avg('pre_test_score') ?? 0, 1);
                $avgAssignment = round($grades->whereNotNull('assignment_score')->avg('assignment_score') ?? 0, 1);
                $avgPostTest = round($grades->whereNotNull('post_test_score')->avg('post_test_score') ?? 0, 1);
                $avgCharacter = round($grades->whereNotNull('character_score')->avg('character_score') ?? 0, 1);
                $avgMemorization = round($grades->whereNotNull('memorization_score')->avg('memorization_score') ?? 0, 1);
            }
        }

        // Peringkat Kelas
        $classRankings = [];
        if ($activeYear && $activeSemester) {
            $classRankings = Classroom::all()->map(function ($class) use ($activeYear, $activeSemester) {
                $avg = StudentGrade::where('class_id', $class->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester_id', $activeSemester->id)
                    ->get()
                    ->avg(function ($g) { return $g->average_score; });
                return [
                    'name' => $class->name,
                    'avg' => $avg ? round($avg, 2) : 0
                ];
            })->sortByDesc('avg')->values();
        }

        return view('pages.pengawas.dashboard', compact(
            'totalTeachers', 'totalStudents', 'totalClasses', 'totalSubjects',
            'schoolAvgGrade', 'avgPreTest', 'avgAssignment', 'avgPostTest', 
            'avgCharacter', 'avgMemorization', 'classRankings', 'activeYear', 'activeSemester'
        ));
    }
}
