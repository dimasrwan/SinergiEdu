<?php

declare(strict_types=1);

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\Feedback;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\StudentParent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard Orang Tua beserta data anak-anaknya.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        // Cari profil orang tua berdasarkan user ID
        $parent = StudentParent::where('user_id', $user->id)->first();

        // Ambil anak-anak dari orang tua tersebut beserta data user & kelasnya
        $children = collect();
        if ($parent) {
            $children = $parent->students()->with(['user', 'classes'])->get();
        }

        $childIds = $children->pluck('id');
        $selectedStudentId = $request->query('student_id', $childIds->first());

        $selectedStudent = null;
        $classroom = null;
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();
        
        $upcomingAssignments = collect();
        $recentGrades = collect();
        $recentFeedbacks = collect();
        $stats = [
            'tugas_aktif' => 0,
            'tugas_selesai' => 0,
            'menunggu_penilaian' => 0,
            'rata_nilai' => null,
        ];

        if ($selectedStudentId) {
            $selectedStudent = $children->firstWhere('id', $selectedStudentId);
            if (!$selectedStudent) {
                $selectedStudent = $children->first();
                $selectedStudentId = $selectedStudent ? $selectedStudent->id : null;
            }
        } else {
            $selectedStudent = $children->first();
            if ($selectedStudent) {
                $selectedStudentId = $selectedStudent->id;
            }
        }
        
        if ($selectedStudent && $classroom = $selectedStudent->activeClassroom()) {

            if ($classroom && $activeYear && $activeSemester) {
                // Tugas Terdekat (Assignments)
                $upcomingAssignments = Assignment::where('class_id', $classroom->id)
                    ->with(['subject', 'submissions' => function ($q) use ($selectedStudentId) {
                        $q->where('student_id', $selectedStudentId);
                    }])
                    ->latest()
                    ->take(3)
                    ->get();
                
                // Hitung statistik tugas di periode aktif untuk kelas ini
                $allAssignments = Assignment::where('class_id', $classroom->id)
                    ->with(['submissions' => function ($q) use ($selectedStudentId) {
                        $q->where('student_id', $selectedStudentId);
                    }])
                    ->get();
                
                foreach ($allAssignments as $assignment) {
                    $submission = $assignment->submissions->first();
                    if (!$submission) {
                        $stats['tugas_aktif']++;
                    } else {
                        $stats['tugas_selesai']++;
                        if ($submission->status === 'submitted' || $submission->status === 'late') {
                            $stats['menunggu_penilaian']++;
                        }
                    }
                }

                // Nilai Terbaru (Grades)
                $gradesQuery = StudentGrade::where('student_id', $selectedStudentId)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester_id', $activeSemester->id);
                
                $recentGrades = (clone $gradesQuery)
                    ->with('subject')
                    ->latest()
                    ->take(3)
                    ->get();

                // Rata-rata Nilai
                $allGrades = $gradesQuery->get();
                $avg = $allGrades->avg('average_score');
                if ($avg !== null) {
                    $stats['rata_nilai'] = round((float)$avg, 1);
                }
            }

            // Feedback Terbaru
            $recentFeedbacks = Feedback::where('student_id', $selectedStudentId)
                ->with(['teacher.user', 'subject'])
                ->latest()
                ->take(3)
                ->get();
        }

        return view('pages.orangtua.dashboard', compact(
            'children',
            'selectedStudentId',
            'selectedStudent',
            'classroom',
            'activeYear',
            'activeSemester',
            'upcomingAssignments',
            'recentGrades',
            'recentFeedbacks',
            'stats'
        ));
    }
}
