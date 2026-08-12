<?php

declare(strict_types=1);

namespace App\Http\Controllers\WakaKurikulum;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Http\Requests\WakaKurikulum\AcademicYearRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    public function index(): View
    {
        $academicYears = AcademicYear::latest()->paginate(10);
        return view('pages.waka.academic-years.index', compact('academicYears'));
    }

    public function create(): View
    {
        return view('pages.waka.academic-years.create');
    }

    public function store(AcademicYearRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        // Jika diset aktif, nonaktifkan tahun ajaran lainnya terlebih dahulu
        if (isset($data['is_active']) && $data['is_active']) {
            AcademicYear::query()->update(['is_active' => false]);
        }

        AcademicYear::create($data);

        return redirect()->route('waka.academic-years.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(AcademicYear $academicYear): View
    {
        return view('pages.waka.academic-years.edit', compact('academicYear'));
    }

    public function update(AcademicYearRequest $request, AcademicYear $academicYear): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['is_active']) && $data['is_active']) {
            AcademicYear::query()->update(['is_active' => false]);
        }

        $academicYear->update($data);

        return redirect()->route('waka.academic-years.index')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(AcademicYear $academicYear): RedirectResponse
    {
        $academicYear->delete();
        return redirect()->route('waka.academic-years.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    /**
     * Ubah status aktif secara cepat.
     */
    public function toggleActive(AcademicYear $academicYear): RedirectResponse
    {
        AcademicYear::query()->update(['is_active' => false]);
        $academicYear->update(['is_active' => true]);

        return redirect()->route('waka.academic-years.index')->with('success', 'Tahun ajaran aktif berhasil diubah.');
    }
}
