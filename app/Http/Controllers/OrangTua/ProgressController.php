<?php

declare(strict_types=1);

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\StudentParent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgressController extends Controller
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

        $selectedStudentId = $request->query('student_id', $childIds->first());

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $grades = collect();
        $selectedStudent = null;
        $classAverages = [];

        if ($selectedStudentId && in_array((int)$selectedStudentId, $childIds->toArray(), true)) {
            $selectedStudent = Student::with('user')->find($selectedStudentId);
            $classId = $selectedStudent->classes()->first()?->id;

            if ($activeYear && $activeSemester) {
                $grades = StudentGrade::where('student_id', $selectedStudentId)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester_id', $activeSemester->id)
                    ->with('subject')
                    ->get();

                // Hitung rata-rata kelas per mata pelajaran untuk perbandingan
                if ($classId) {
                    foreach ($grades as $grade) {
                        $avgQuery = StudentGrade::where('class_id', $classId)
                            ->where('subject_id', $grade->subject_id)
                            ->where('academic_year_id', $activeYear->id)
                            ->where('semester_id', $activeSemester->id);

                        $classAverages[$grade->subject_id] = [
                            'pre_test' => round($avgQuery->avg('pre_test_score') ?? 0, 1),
                            'assignment' => round($avgQuery->avg('assignment_score') ?? 0, 1),
                            'post_test' => round($avgQuery->avg('post_test_score') ?? 0, 1),
                            'character' => round($avgQuery->avg('character_score') ?? 0, 1),
                            'memorization' => round($avgQuery->avg('memorization_score') ?? 0, 1),
                        ];
                    }
                }
            }
        }

        return view('pages.orangtua.progress.index', compact(
            'children',
            'selectedStudentId',
            'selectedStudent',
            'grades',
            'classAverages',
            'activeYear',
            'activeSemester'
        ));
    }
}
