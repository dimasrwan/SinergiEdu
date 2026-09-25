<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InspectionController extends Controller
{
    public function index(): View
    {
        $activeSchoolId = session('pengawas_school_id');

        $inspections = Inspection::query()
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->when($activeSchoolId, fn ($q) => $q->where('school_id', $activeSchoolId))
            ->with('school', 'createdBy')
            ->latest()
            ->paginate(10);

        return view('pages.pengawas.inspections.index', compact('inspections'));
    }

    public function create(): View
    {
        $activeSchoolId = session('pengawas_school_id');

        $schools = School::query()
            ->when($activeSchoolId, fn ($q) => $q->where('id', $activeSchoolId))
            ->get();

        return view('pages.pengawas.inspections.create', compact('schools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'nullable|string',
            'inspection_date' => 'nullable|date',
            'location' => 'nullable|string|max:200',
            'status' => 'nullable|in:pending,scheduled,completed',
            'school_id' => 'nullable|exists:schools,id',
        ]);

        $validated['created_by'] = auth()->id();

        Inspection::create($validated);

        return redirect()->route('pengawas.inspections.index')->with('success', 'Jadwal inspeksi berhasil dibuat.');
    }

    public function show(Inspection $inspection): View
    {
        $activeSchoolId = session('pengawas_school_id');
        if ($activeSchoolId && (int) $inspection->school_id !== (int) $activeSchoolId) {
            abort(403, 'Akses ditolak.');
        }

        return view('pages.pengawas.inspections.show', compact('inspection'));
    }

    public function edit(Inspection $inspection): View
    {
        $activeSchoolId = session('pengawas_school_id');
        if ($activeSchoolId && (int) $inspection->school_id !== (int) $activeSchoolId) {
            abort(403, 'Akses ditolak.');
        }

        $schools = School::query()
            ->when($activeSchoolId, fn ($q) => $q->where('id', $activeSchoolId))
            ->get();

        return view('pages.pengawas.inspections.edit', compact('inspection', 'schools'));
    }

    public function update(Request $request, Inspection $inspection): RedirectResponse
    {
        $activeSchoolId = session('pengawas_school_id');
        if ($activeSchoolId && (int) $inspection->school_id !== (int) $activeSchoolId) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'nullable|string',
            'inspection_date' => 'nullable|date',
            'location' => 'nullable|string|max:200',
            'status' => 'nullable|in:pending,scheduled,completed',
            'school_id' => 'nullable|exists:schools,id',
        ]);

        $inspection->update($validated);

        return redirect()->route('pengawas.inspections.index')->with('success', 'Jadwal inspeksi berhasil diperbarui.');
    }

    public function destroy(Inspection $inspection): RedirectResponse
    {
        $activeSchoolId = session('pengawas_school_id');
        if ($activeSchoolId && (int) $inspection->school_id !== (int) $activeSchoolId) {
            abort(403, 'Akses ditolak.');
        }

        $inspection->delete();

        return redirect()->route('pengawas.inspections.index')->with('success', 'Jadwal inspeksi berhasil dihapus.');
    }

    /**
     * Arsipkan jadwal inspeksi.
     */
    public function archive(Inspection $inspection): RedirectResponse
    {
        $activeSchoolId = session('pengawas_school_id');
        if ($activeSchoolId && (int) $inspection->school_id !== (int) $activeSchoolId) {
            abort(403, 'Akses ditolak.');
        }

        $inspection->update(['is_archived' => true]);

        return redirect()->route('pengawas.inspections.index')->with('success', 'Jadwal inspeksi berhasil diarsipkan.');
    }

    /**
     * Batalkan arsip jadwal inspeksi.
     */
    public function unarchive(Inspection $inspection): RedirectResponse
    {
        $activeSchoolId = session('pengawas_school_id');
        if ($activeSchoolId && (int) $inspection->school_id !== (int) $activeSchoolId) {
            abort(403, 'Akses ditolak.');
        }

        $inspection->update(['is_archived' => false]);

        return redirect()->route('pengawas.inspections.index')->with('success', 'Arsip jadwal inspeksi berhasil dibatalkan.');
    }

    /**
     * Tampilkan daftar jadwal inspeksi yang diarsipkan.
     */
    public function archived(): View
    {
        $activeSchoolId = session('pengawas_school_id');

        $archived = Inspection::archived()
            ->when($activeSchoolId, fn ($q) => $q->where('school_id', $activeSchoolId))
            ->with('school', 'createdBy')
            ->latest()
            ->paginate(10);

        return view('pages.pengawas.inspections.archived', compact('archived'));
    }
}