<?php

declare(strict_types=1);

namespace App\Http\Controllers\WakaKurikulum;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Http\Requests\WakaKurikulum\SemesterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SemesterController extends Controller
{
    public function index(): View
    {
        $semesters = Semester::with('academicYear')->latest()->paginate(10);
        return view('pages.waka.semesters.index', compact('semesters'));
    }

    public function create(): View
    {
        $academicYears = AcademicYear::all();
        return view('pages.waka.semesters.create', compact('academicYears'));
    }

    public function store(SemesterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['is_active']) && $data['is_active']) {
            Semester::query()->update(['is_active' => false]);
        }

        Semester::create($data);

        return redirect()->route('waka.semesters.index')->with('success', 'Semester berhasil ditambahkan.');
    }

    public function edit(Semester $semester): View
    {
        $academicYears = AcademicYear::all();
        return view('pages.waka.semesters.edit', compact('semester', 'academicYears'));
    }

    public function update(SemesterRequest $request, Semester $semester): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['is_active']) && $data['is_active']) {
            Semester::query()->update(['is_active' => false]);
        }

        $semester->update($data);

        return redirect()->route('waka.semesters.index')->with('success', 'Semester berhasil diperbarui.');
    }

    public function destroy(Semester $semester): RedirectResponse
    {
        $semester->delete();
        return redirect()->route('waka.semesters.index')->with('success', 'Semester berhasil dihapus.');
    }

    public function toggleActive(Semester $semester): RedirectResponse
    {
        Semester::query()->update(['is_active' => false]);
        $semester->update(['is_active' => true]);

        return redirect()->route('waka.semesters.index')->with('success', 'Semester aktif berhasil diubah.');
    }
}
