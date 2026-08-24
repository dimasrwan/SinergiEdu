<?php

declare(strict_types=1);

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Feedback;
use App\Models\Material;
use App\Models\Student;
use App\Models\StudentGrade;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private function getStudentProfile(): Student
    {
        return Student::where('user_id', auth()->id())->firstOrFail();
    }

    /**
     * Tampilkan halaman dashboard Siswa.
     */
    public function index(): View
    {
        $student = $this->getStudentProfile();
        $classroom = $student->activeClassroom();

        $stats = [
            'total_tugas' => 0,
            'tugas_selesai' => 0,
            'progres' => 0,
            'rata_rata' => 'Belum Ada',
        ];

        $upcomingAssignments = collect();
        $recentMaterials = collect();
        $recentFeedback = null;

        if ($classroom) {
            $activeYear = AcademicYear::where('is_active', true)->first();

            // Stats Tugas
            $stats['total_tugas'] = Assignment::where('class_id', $classroom->id)->count();
            
            // Tugas Selesai berdasarkan assignment_submissions di kelas aktif
            $stats['tugas_selesai'] = AssignmentSubmission::where('student_id', $student->id)
                ->whereHas('assignment', function ($q) use ($classroom) {
                    $q->where('class_id', $classroom->id);
                })
                ->count();
            
            if ($stats['total_tugas'] > 0) {
                $stats['progres'] = round(($stats['tugas_selesai'] / $stats['total_tugas']) * 100);
            }

            // Rata-rata Nilai
            $avgScore = StudentGrade::where('student_id', $student->id)
                ->where('class_id', $classroom->id)
                ->where('academic_year_id', $activeYear?->id)
                ->avg('assignment_score');
                
            if ($avgScore !== null) {
                $stats['rata_rata'] = round((float)$avgScore, 1);
            }

            // Upcoming Assignments
            $upcomingAssignments = Assignment::where('class_id', $classroom->id)
                ->where('deadline', '>', now())
                ->whereDoesntHave('submissions', function ($q) use ($student) {
                    $q->where('student_id', $student->id);
                })
                ->with('subject')
                ->orderBy('deadline', 'asc')
                ->take(2)
                ->get();

            // Recent Materials
            $recentMaterials = Material::where('class_id', $classroom->id)
                ->with(['teacher.user', 'subject'])
                ->latest()
                ->take(2)
                ->get();

            // Recent Feedback
            $recentFeedback = Feedback::where('student_id', $student->id)
                ->with(['teacher.user', 'subject'])
                ->latest()
                ->first();
        }

        return view('pages.siswa.dashboard', compact(
            'classroom',
            'stats',
            'upcomingAssignments',
            'recentMaterials',
            'recentFeedback'
        ));
    }
}
