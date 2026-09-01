<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ActionPlan;
use App\Models\Classroom;
use App\Models\Semester;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActionPlanController extends Controller
{
    public function index(): View
    {
        $plans = ActionPlan::where('pengawas_user_id', auth()->id())
            ->with('classroom', 'academicYear', 'semester')
            ->latest()
            ->paginate(15);

        return view('pages.pengawas.action-plans.index', compact('plans'));
    }

    public function create(): View
    {
        $classrooms   = Classroom::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('id')->get();
        $semesters    = Semester::orderBy('id')->get();

        return view('pages.pengawas.action-plans.create', compact('classrooms', 'academicYears', 'semesters'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string|min:10',
            'priority'         => 'required|in:high,medium,low',
            'status'           => 'required|in:draft,published',
            'class_id'         => 'nullable|exists:classes,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester_id'      => 'nullable|exists:semesters,id',
        ]);

        $data['pengawas_user_id'] = auth()->id();

        ActionPlan::create($data);

        return redirect()->route('pengawas.action-plans.index')
            ->with('success', 'Rencana aksi berhasil disimpan.');
    }

    public function edit(ActionPlan $actionPlan): View
    {
        abort_if($actionPlan->pengawas_user_id !== auth()->id(), 403);

        $classrooms    = Classroom::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('id')->get();
        $semesters     = Semester::orderBy('id')->get();

        return view('pages.pengawas.action-plans.edit', compact('actionPlan', 'classrooms', 'academicYears', 'semesters'));
    }

    public function update(Request $request, ActionPlan $actionPlan): RedirectResponse
    {
        abort_if($actionPlan->pengawas_user_id !== auth()->id(), 403);

        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string|min:10',
            'priority'         => 'required|in:high,medium,low',
            'status'           => 'required|in:draft,published',
            'class_id'         => 'nullable|exists:classes,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester_id'      => 'nullable|exists:semesters,id',
        ]);

        $actionPlan->update($data);

        return redirect()->route('pengawas.action-plans.index')
            ->with('success', 'Rencana aksi berhasil diperbarui.');
    }

    public function destroy(ActionPlan $actionPlan): RedirectResponse
    {
        abort_if($actionPlan->pengawas_user_id !== auth()->id(), 403);

        $actionPlan->delete();

        return redirect()->route('pengawas.action-plans.index')
            ->with('success', 'Rencana aksi berhasil dihapus.');
    }
}
