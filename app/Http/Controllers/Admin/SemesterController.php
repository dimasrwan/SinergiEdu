<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', \App\Models\Semester::class);
        $query = Semester::with('academicYear')
            ->select('semesters.*')
            ->join('academic_years', 'semesters.academic_year_id', '=', 'academic_years.id');

        if ($request->filled('academic_year_id')) {
            $query->where('semesters.academic_year_id', $request->academic_year_id);
        }

        $semesters = $query->orderByDesc('academic_years.year')
                           ->orderByRaw("CASE WHEN semesters.name = 'Genap' THEN 1 WHEN semesters.name = 'Ganjil' THEN 2 ELSE 3 END")
                           ->paginate(10)
                           ->withQueryString();

        $academicYears = AcademicYear::orderByDesc('year')->get();

        return view('pages.admin.semesters.index', compact('semesters', 'academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', \App\Models\Semester::class);
        $academicYears = AcademicYear::orderByDesc('year')->get();
        return view('pages.admin.semesters.create', compact('academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', \App\Models\Semester::class);
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => [
                'required',
                'string',
                'in:Ganjil,Genap',
                Rule::unique('semesters')->where(function ($query) use ($request) {
                    return $query->where('academic_year_id', $request->academic_year_id)
                                 ->where('name', $request->name);
                })
            ],
            'is_active' => 'nullable|boolean',
        ], [
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
            'name.unique' => 'Semester ini sudah terdaftar pada tahun ajaran yang dipilih.',
            'name.in' => 'Nama semester harus Ganjil atau Genap.',
        ]);

        $isActive = $request->has('is_active');

        // Business rule: Hanya satu semester yang boleh aktif secara global (karena satu tahun ajaran aktif -> satu semester aktif)
        // Atau: Hanya satu semester aktif PER TAHUN AJARAN. Sesuai instruksi: "Untuk satu Tahun Ajaran, hanya satu Semester yang dapat aktif pada satu waktu."
        if ($isActive) {
            Semester::where('academic_year_id', $validated['academic_year_id'])
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
        }

        Semester::create([
            'academic_year_id' => $validated['academic_year_id'],
            'name' => $validated['name'],
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.semesters.index')->with('success', 'Semester berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Semester $semester)
    {
        Gate::authorize('view', $semester);
        $semester->load('academicYear');
        
        // Count student grades directly from DB
        $studentGradesCount = \DB::table('student_grades')->where('semester_id', $semester->id)->count();

        return view('pages.admin.semesters.show', compact('semester', 'studentGradesCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Semester $semester)
    {
        Gate::authorize('update', $semester);
        $academicYears = AcademicYear::orderByDesc('year')->get();
        return view('pages.admin.semesters.edit', compact('semester', 'academicYears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Semester $semester)
    {
        Gate::authorize('update', $semester);
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => [
                'required',
                'string',
                'in:Ganjil,Genap',
                Rule::unique('semesters')->where(function ($query) use ($request, $semester) {
                    return $query->where('academic_year_id', $request->academic_year_id)
                                 ->where('name', $request->name)
                                 ->where('id', '!=', $semester->id);
                })
            ],
            'is_active' => 'nullable|boolean',
        ], [
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
            'name.unique' => 'Semester ini sudah terdaftar pada tahun ajaran yang dipilih.',
            'name.in' => 'Nama semester harus Ganjil atau Genap.',
        ]);

        $isActive = $request->has('is_active');

        if ($isActive && !$semester->is_active) {
            Semester::where('academic_year_id', $validated['academic_year_id'])
                    ->where('id', '!=', $semester->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
        }

        $semester->update([
            'academic_year_id' => $validated['academic_year_id'],
            'name' => $validated['name'],
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.semesters.index')->with('success', 'Semester berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Semester $semester)
    {
        Gate::authorize('delete', $semester);
        $dependenciesCount = \DB::table('student_grades')->where('semester_id', $semester->id)->count();

        if ($dependenciesCount > 0) {
            return redirect()->route('admin.semesters.index')->with('error', 'Semester ini masih digunakan oleh data akademik (penilaian siswa) dan tidak dapat dihapus.');
        }

        $semester->delete();

        return redirect()->route('admin.semesters.index')->with('success', 'Semester berhasil dihapus.');
    }
}
