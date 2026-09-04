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
        $inspections = Inspection::query()
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->when(auth()->user()->school_id, fn ($q) => $q->where('school_id', auth()->user()->school_id))
            ->with('school', 'createdBy')
            ->latest()
            ->paginate(10);

        return view('pages.pengawas.inspections.index', compact('inspections'));
    }

    public function create(): View
    {
        $schools = School::query()
            ->when(auth()->user()->school_id, fn ($q) => $q->where('id', auth()->user()->school_id))
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
        return view('pages.pengawas.inspections.show', compact('inspection'));
    }

    public function edit(Inspection $inspection): View
    {
        $schools = School::all();
        return view('pages.pengawas.inspections.edit', compact('inspection', 'schools'));
    }

    public function update(Request $request, Inspection $inspection): RedirectResponse
    {
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
        $inspection->delete();

        return redirect()->route('pengawas.inspections.index')->with('success', 'Jadwal inspeksi berhasil dihapus.');
    }

    /**
     * Arsipkan jadwal inspeksi.
     */
    public function archive(Inspection $inspection): RedirectResponse
    {
        $inspection->update(['is_archived' => true]);

        return redirect()->route('pengawas.inspections.index')->with('success', 'Jadwal inspeksi berhasil diarsipkan.');
    }

    /**
     * Batalkan arsip jadwal inspeksi.
     */
    public function unarchive(Inspection $inspection): RedirectResponse
    {
        $inspection->update(['is_archived' => false]);

        return redirect()->route('pengawas.inspections.index')->with('success', 'Arsip jadwal inspeksi berhasil dibatalkan.');
    }

    /**
     * Tampilkan daftar jadwal inspeksi yang diarsipkan.
     */
    public function archived(): View
    {
        $archived = Inspection::archived()
            ->when(auth()->user()->school_id, fn ($q) => $q->where('school_id', auth()->user()->school_id))
            ->with('school', 'createdBy')
            ->latest()
            ->paginate(10);

        return view('pages.pengawas.inspections.archived', compact('archived'));
    }
}