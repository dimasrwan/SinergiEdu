<?php

declare(strict_types=1);

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Semester;
use App\Models\StudentParent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    private function getParentProfile(): StudentParent
    {
        return StudentParent::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(Request $request): View
    {
        $parent = $this->getParentProfile();
        $children = $parent->students()->with(['user', 'classes'])->get();
        $childIds = $children->pluck('id');

        $selectedStudentId = $request->query('student_id');

        if ($selectedStudentId) {
            $selectedStudent = $children->firstWhere('id', $selectedStudentId);
            if (!$selectedStudent) {
                $selectedStudent = $children->first();
                $selectedStudentId = $selectedStudent ? $selectedStudent->id : null;
            }
        } else {
            $selectedStudent = $children->first();
            $selectedStudentId = $selectedStudent ? $selectedStudent->id : null;
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $assignments = collect();
        $stats = [
            'total_tugas' => 0,
            'selesai' => 0,
            'menunggu_penilaian' => 0,
            'belum_dikumpulkan' => 0,
        ];

        if ($selectedStudent && $classroom = $selectedStudent->activeClassroom()) {
            if ($activeYear && $activeSemester) {
                // Base query restricted to the active classroom of the child
                $baseQuery = Assignment::where('class_id', $classroom->id);

                // Paginate for list
                $assignments = (clone $baseQuery)
                    ->with(['subject', 'teacher.user', 'learningMeeting', 'material', 'submissions' => function ($query) use ($selectedStudentId) {
                        $query->where('student_id', $selectedStudentId);
                    }])
                    ->latest()
                    ->paginate(10);
                
                // Aggregation
                $totalAssignments = (clone $baseQuery)->count();
                $stats['total_tugas'] = $totalAssignments;

                // Submissions counts
                $submissionCounts = AssignmentSubmission::whereIn('assignment_id', (clone $baseQuery)->pluck('id'))
                    ->where('student_id', $selectedStudentId)
                    ->selectRaw('
                        COUNT(id) as total_submitted,
                        SUM(CASE WHEN score IS NULL THEN 1 ELSE 0 END) as menunggu
                    ')
                    ->first();
                
                $stats['selesai'] = (int)($submissionCounts->total_submitted ?? 0);
                $stats['menunggu_penilaian'] = (int)($submissionCounts->menunggu ?? 0);
                $stats['belum_dikumpulkan'] = $stats['total_tugas'] - $stats['selesai'];
            }
        }

        return view('pages.orangtua.assignments.index', compact(
            'children',
            'selectedStudentId',
            'selectedStudent',
            'assignments',
            'activeYear',
            'activeSemester',
            'stats'
        ));
    }
}
