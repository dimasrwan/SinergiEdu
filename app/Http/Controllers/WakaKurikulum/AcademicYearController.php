<?php

declare(strict_types=1);

namespace App\Http\Controllers\WakaKurikulum;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Http\Requests\WakaKurikulum\AcademicYearRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $schoolId = app(\App\Services\TenantService::class)->getSchoolId() ?? auth()->user()->school_id;
        $data['school_id'] = $schoolId;
        
        try {
            DB::transaction(function () use ($data, $schoolId) {
                if (isset($data['is_active']) && $data['is_active']) {
                    AcademicYear::where('school_id', $schoolId)->where('is_active', true)->update(['is_active' => false]);
                }
                AcademicYear::create($data);
            });
            return redirect()->route('waka.academic-years.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan tahun ajaran: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan tahun ajaran. Silakan coba lagi.')->withInput();
        }
    }

    public function edit(AcademicYear $academicYear): View
    {
        return view('pages.waka.academic-years.edit', compact('academicYear'));
    }

    public function update(AcademicYearRequest $request, AcademicYear $academicYear): RedirectResponse
    {
        $data = $request->validated();
        $schoolId = $academicYear->school_id ?? app(\App\Services\TenantService::class)->getSchoolId() ?? auth()->user()->school_id;

        try {
            DB::transaction(function () use ($data, $academicYear, $schoolId) {
                if (isset($data['is_active']) && $data['is_active'] && !$academicYear->is_active) {
                    AcademicYear::where('school_id', $schoolId)
                        ->where('id', '!=', $academicYear->id)
                        ->where('is_active', true)
                        ->update(['is_active' => false]);
                }
                $academicYear->update($data);
            });
            return redirect()->route('waka.academic-years.index')->with('success', 'Tahun ajaran berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui tahun ajaran: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui tahun ajaran. Silakan coba lagi.')->withInput();
        }
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
        $schoolId = $academicYear->school_id ?? app(\App\Services\TenantService::class)->getSchoolId() ?? auth()->user()->school_id;

        try {
            DB::transaction(function () use ($academicYear, $schoolId) {
                AcademicYear::where('school_id', $schoolId)->where('is_active', true)->update(['is_active' => false]);
                $academicYear->update(['is_active' => true]);
            });
            return redirect()->route('waka.academic-years.index')->with('success', 'Tahun ajaran aktif berhasil diubah.');
        } catch (\Exception $e) {
            Log::error('Gagal mengubah tahun ajaran aktif: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah periode aktif. Silakan coba lagi.');
        }
    }
}
