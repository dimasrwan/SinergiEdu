<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AcademicYear::query()->withCount('classes');

        if ($request->filled('status')) {
            $isActive = $request->status === 'aktif';
            $query->where('is_active', $isActive);
        }

        $academicYears = $query->orderByDesc('year')->paginate(10)->withQueryString();

        return view('pages.admin.academic-years.index', compact('academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.academic-years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|string|max:20|unique:academic_years,year',
            'is_active' => 'nullable|boolean',
        ], [
            'year.unique' => 'Tahun ajaran ini sudah terdaftar.',
            'year.required' => 'Tahun ajaran wajib diisi.',
        ]);

        $isActive = $request->has('is_active');

        // Business rule: Hanya satu tahun ajaran yang boleh aktif
        if ($isActive) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        AcademicYear::create([
            'year' => $validated['year'],
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.academic-years.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicYear $academicYear)
    {
        $academicYear->load(['classes.homeroomTeacher.user']);
        
        return view('pages.admin.academic-years.show', compact('academicYear'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear)
    {
        return view('pages.admin.academic-years.edit', compact('academicYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'year' => 'required|string|max:20|unique:academic_years,year,' . $academicYear->id,
            'is_active' => 'nullable|boolean',
        ], [
            'year.unique' => 'Tahun ajaran ini sudah terdaftar.',
            'year.required' => 'Tahun ajaran wajib diisi.',
        ]);

        $isActive = $request->has('is_active');

        // Business rule: Hanya satu tahun ajaran yang boleh aktif
        if ($isActive && !$academicYear->is_active) {
            AcademicYear::where('id', '!=', $academicYear->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $academicYear->update([
            'year' => $validated['year'],
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.academic-years.index')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        // Cek dependensi pada tabel yang menggunakan academic_year_id
        $dependenciesCount = 0;
        
        // 1. Classes
        $dependenciesCount += \DB::table('classes')->where('academic_year_id', $academicYear->id)->count();
        // 2. Semesters
        $dependenciesCount += \DB::table('semesters')->where('academic_year_id', $academicYear->id)->count();
        // 3. Student Classes (Pivot)
        $dependenciesCount += \DB::table('student_classes')->where('academic_year_id', $academicYear->id)->count();
        // 4. Student Grades
        $dependenciesCount += \DB::table('student_grades')->where('academic_year_id', $academicYear->id)->count();

        if ($dependenciesCount > 0) {
            return redirect()->route('admin.academic-years.index')->with('error', 'Tahun ajaran ini masih digunakan oleh data akademik (seperti data kelas atau nilai) dan tidak dapat dihapus.');
        }

        $academicYear->delete();

        return redirect()->route('admin.academic-years.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
