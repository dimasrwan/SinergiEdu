<?php

declare(strict_types=1);

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ParentSupport;
use App\Models\Semester;
use App\Models\StudentParent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportController extends Controller
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

        $supports = ParentSupport::whereIn('student_id', $childIds)
            ->when($selectedStudentId, function ($query) use ($selectedStudentId) {
                return $query->where('student_id', $selectedStudentId);
            })
            ->with(['student.user', 'academicYear', 'semester'])
            ->latest()
            ->paginate(10);

        return view('pages.orangtua.support.index', compact(
            'children',
            'selectedStudentId',
            'supports',
            'activeYear',
            'activeSemester'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $parent = $this->getParentProfile();
        $childIds = $parent->students()->pluck('id')->toArray();

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id', function ($attribute, $value, $fail) use ($childIds) {
                if (!in_array((int)$value, $childIds, true)) {
                    $fail('Anda hanya dapat mengisi dukungan untuk anak Anda sendiri.');
                }
            }],
            'week_number' => 'required|string|max:50',
            'support_description' => 'required|string|max:1000',
            'general_feedback' => 'nullable|string|max:1000',
            'action_plan' => 'nullable|string|max:1000',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        ParentSupport::create([
            'school_id' => auth()->user()->school_id,
            'student_id' => $validated['student_id'],
            'academic_year_id' => $activeYear?->id,
            'semester_id' => $activeSemester?->id,
            'week_number' => $validated['week_number'],
            'support_description' => $validated['support_description'],
            'general_feedback' => $validated['general_feedback'] ?? null,
            'action_plan' => $validated['action_plan'] ?? null,
        ]);

        return redirect()->route('orangtua.support.index', ['student_id' => $validated['student_id']])
            ->with('success', 'Dukungan, umpan balik, dan rencana aksi orang tua berhasil disimpan!');
    }
}
