<?php

declare(strict_types=1);

namespace App\Http\Controllers\WakaKurikulum;

use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\StudentAssessment;
use App\Models\LearningMeeting;
use App\Models\AssignmentSubmission;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard Waka Kurikulum.
     */
    public function index(): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $stats = [
            'total_students' => Student::count(),
            'total_classes' => Classroom::count(),
            'avg_grade' => StudentAssessment::whereHas('learningMeeting', function ($q) use ($activeYear, $activeSemester) {
                $q->where('academic_year_id', $activeYear->id ?? 0)
                  ->where('semester_id', $activeSemester->id ?? 0);
            })->get()->avg(fn($a) => $a->average_score) ?? 0,
            'meeting_count' => LearningMeeting::where('academic_year_id', $activeYear->id ?? 0)->count(),
            'risky_students' => 12, // dummy
            'max_juz' => StudentAssessment::max('memorization_juz'),
            'max_ayat' => StudentAssessment::max('memorization_ayat'),
            'submission_trend' => [
                ['date' => '2026-09-02', 'count' => 45],
                ['date' => '2026-09-01', 'count' => 38],
                ['date' => '2026-08-31', 'count' => 52],
            ], // dummy
        ];

        return view('pages.waka.dashboard', compact('stats'));
    }
}
