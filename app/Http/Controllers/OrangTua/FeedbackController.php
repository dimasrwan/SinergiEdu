<?php

declare(strict_types=1);

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\StudentParent;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    private function getParentProfile(): StudentParent
    {
        return StudentParent::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(\Illuminate\Http\Request $request): View
    {
        $parent = $this->getParentProfile();
        $children = $parent->students()->with(['user', 'classes'])->get();
        $childIds = $children->pluck('id')->toArray();

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

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        // Feedbacks are just for the selected child.
        // We will restrict to active context if they have those relationships.
        // Wait, Feedback model doesn't have academic_year_id/semester_id, but we can filter by the child's active class teachers/subjects if strict.
        // However, the rule says "Jika Feedback tidak memiliki field academic_year_id/semester_id: gunakan relationship/context existing yang tersedia. JANGAN menambahkan kolom baru".
        // The safest is to just show feedback for the selected child, latest first.
        // Wait, the user said: "Feedback yang ditampilkan pada index harus mengikuti: current school + selected child + active academic year + active semester jika field/relationship Feedback memang mendukung context tersebut. Jika Feedback tidak memiliki field... gunakan relationship/context existing yang tersedia."
        // We can just filter by student_id and order by created_at.

        $feedbacks = collect();
        if ($selectedStudentId) {
            $query = Feedback::where('student_id', $selectedStudentId)
                ->with(['teacher.user', 'student.user', 'subject'])
                ->latest();

            // if search
            if ($request->has('search') && $request->query('search') !== '') {
                $search = $request->query('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('teacher.user', function ($t) use ($search) {
                          $t->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('subject', function ($s) use ($search) {
                          $s->where('name', 'like', "%{$search}%");
                      });
                });
            }

            $feedbacks = $query->paginate(15)->withQueryString();
        } else {
            $feedbacks = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
        }

        return view('pages.orangtua.feedbacks.index', compact(
            'feedbacks',
            'children',
            'selectedStudent',
            'selectedStudentId'
        ));
    }

    public function show(Feedback $feedback): View
    {
        $parent = $this->getParentProfile();
        $childIds = $parent->students()->pluck('students.id')->toArray();

        // Strict IDOR protection
        if (!in_array($feedback->student_id, $childIds, true)) {
            abort(403, 'Akses ditolak.');
        }

        $feedback->load(['teacher.user', 'student.user', 'subject']);

        return view('pages.orangtua.feedbacks.show', compact('feedback'));
    }
}
