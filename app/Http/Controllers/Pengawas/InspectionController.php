<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Inspection;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InspectionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Inspection::where('pengawas_user_id', auth()->id())
            ->with(['teacher.user', 'classroom']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inspections = $query->orderBy('inspection_date')->paginate(15)->withQueryString();

        return view('pages.pengawas.inspections.index', compact('inspections'));
    }

    public function create(): View
    {
        $teachers = Teacher::with('user')->get();
        $classrooms = Classroom::orderBy('name')->get();

        return view('pages.pengawas.inspections.create', compact('teachers', 'classrooms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'class_id' => 'nullable|exists:classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'inspection_date' => 'required|date',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
        ]);

        $data['pengawas_user_id'] = auth()->id();

        Inspection::create($data);

        return redirect()->route('pengawas.inspections.index')
            ->with('success', 'Jadwal inspeksi berhasil dibuat.');
    }

    public function edit(Inspection $inspection): View
    {
        abort_if($inspection->pengawas_user_id !== auth()->id(), 403);

        $teachers = Teacher::with('user')->get();
        $classrooms = Classroom::orderBy('name')->get();

        return view('pages.pengawas.inspections.edit', compact('inspection', 'teachers', 'classrooms'));
    }

    public function update(Request $request, Inspection $inspection): RedirectResponse
    {
        abort_if($inspection->pengawas_user_id !== auth()->id(), 403);

        $data = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'class_id' => 'nullable|exists:classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'inspection_date' => 'required|date',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $inspection->update($data);

        return redirect()->route('pengawas.inspections.index')
            ->with('success', 'Jadwal inspeksi berhasil diperbarui.');
    }

    public function destroy(Inspection $inspection): RedirectResponse
    {
        abort_if($inspection->pengawas_user_id !== auth()->id(), 403);

        $inspection->delete();

        return redirect()->route('pengawas.inspections.index')
            ->with('success', 'Jadwal inspeksi berhasil dihapus.');
    }
}
