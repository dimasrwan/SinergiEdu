<?php

declare(strict_types=1);

namespace App\Http\Controllers\WakaKurikulum;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            $activeYear = AcademicYear::where('is_active', true)->first();
            if (!$activeYear || $data['academic_year_id'] != $activeYear->id) {
                return redirect()->back()->with('error', 'Semester ini tidak dapat diaktifkan karena tahun ajarannya sedang tidak aktif.')->withInput();
            }
        }

        try {
            DB::transaction(function () use ($data) {
                if (isset($data['is_active']) && $data['is_active']) {
                    Semester::query()->update(['is_active' => false]);
                }
                Semester::create($data);
            });
            return redirect()->route('waka.semesters.index')->with('success', 'Semester berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan semester: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah periode aktif. Silakan coba lagi.')->withInput();
        }
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
            $activeYear = AcademicYear::where('is_active', true)->first();
            if (!$activeYear || $data['academic_year_id'] != $activeYear->id) {
                return redirect()->back()->with('error', 'Semester ini tidak dapat diaktifkan karena tahun ajarannya sedang tidak aktif.')->withInput();
            }
        }

        try {
            DB::transaction(function () use ($data, $semester) {
                if (isset($data['is_active']) && $data['is_active']) {
                    Semester::query()->update(['is_active' => false]);
                }
                $semester->update($data);
            });
            return redirect()->route('waka.semesters.index')->with('success', 'Semester berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui semester: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah periode aktif. Silakan coba lagi.')->withInput();
        }
    }

    public function destroy(Semester $semester): RedirectResponse
    {
        $semester->delete();
        return redirect()->route('waka.semesters.index')->with('success', 'Semester berhasil dihapus.');
    }

    public function toggleActive(Semester $semester): RedirectResponse
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear || $semester->academic_year_id != $activeYear->id) {
            return redirect()->back()->with('error', 'Semester ini tidak dapat diaktifkan karena tahun ajarannya sedang tidak aktif.');
        }

        try {
            DB::transaction(function () use ($semester) {
                Semester::query()->update(['is_active' => false]);
                $semester->update(['is_active' => true]);
            });
            return redirect()->route('waka.semesters.index')->with('success', 'Semester aktif berhasil diubah.');
        } catch (\Exception $e) {
            Log::error('Gagal mengubah semester aktif: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah periode aktif. Silakan coba lagi.');
        }
    }
}
