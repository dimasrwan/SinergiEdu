<?php

declare(strict_types=1);

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Assignment;
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

        $selectedStudentId = $request->query('student_id', $childIds->first());

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $assignments = collect();
        $selectedStudent = null;

        if ($selectedStudentId && in_array((int)$selectedStudentId, $childIds->toArray(), true)) {
            $selectedStudent = $children->find($selectedStudentId);
            $classId = $selectedStudent->classes()->first()?->id;

            if ($classId) {
                $assignments = Assignment::where('class_id', $classId)
                    ->with(['subject', 'teacher.user', 'submissions' => function ($query) use ($selectedStudentId) {
                        $query->where('student_id', $selectedStudentId);
                    }])
                    ->latest()
                    ->paginate(10);
            }
        }

        return view('pages.orangtua.assignments.index', compact(
            'children',
            'selectedStudentId',
            'selectedStudent',
            'assignments',
            'activeYear',
            'activeSemester'
        ));
    }
}
