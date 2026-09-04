<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\MaterialRequest;
use App\Models\AcademicYear;
use App\Models\Material;
use App\Models\Semester;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    private function getTeacherProfile(): Teacher
    {
        return Teacher::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(Request $request): View
    {
        $teacher = $this->getTeacherProfile();
        
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();
        
        // Get active teacher_subject IDs to filter materials for the current semester
        $activeClassIds = [];
        $activeSubjectIds = [];
        
        if ($activeAcademicYear && $activeSemester) {
            $teacherSubjects = TeacherSubject::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('semester_id', $activeSemester->id)
                ->get();
                
            $activeClassIds = $teacherSubjects->pluck('class_id')->unique()->toArray();
            $activeSubjectIds = $teacherSubjects->pluck('subject_id')->unique()->toArray();
        }

        $query = Material::where('teacher_id', $teacher->id)
            ->with(['classroom', 'subject']);
            
        // Default to active context if available, otherwise show all
        if (!empty($activeClassIds) && !empty($activeSubjectIds)) {
            $query->whereIn('class_id', $activeClassIds)
                  ->whereIn('subject_id', $activeSubjectIds);
        }
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $materials = $query->latest()->paginate(10);

        return view('pages.guru.materials.index', compact('materials'));
    }

    public function create(): View
    {
        $teacher = $this->getTeacherProfile();
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();
        
        $classes = collect();
        $subjects = collect();
        
        if ($activeAcademicYear && $activeSemester) {
            $teacherSubjects = TeacherSubject::with(['classroom', 'subject'])
                ->where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('semester_id', $activeSemester->id)
                ->get();
                
            // Get unique classes and subjects
            $classes = $teacherSubjects->pluck('classroom')->unique('id')->values();
            $subjects = $teacherSubjects->pluck('subject')->unique('id')->values();
        }

        return view('pages.guru.materials.create', compact('classes', 'subjects', 'meetings'));
    }

    public function store(MaterialRequest $request): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        $data = $request->validated();
        $data['teacher_id'] = $teacher->id;

        $this->ensureMeetingMatchesMaterial($data, $teacher);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('materials/pdfs', 'local');
        }

        if ($request->hasFile('video')) {
            $data['video_path'] = $request->file('video')->store('materials/videos', 'local');
        }

        Material::create($data);

        return redirect()->route('guru.materials.index')->with('success', 'Materi pembelajaran berhasil diunggah.');
    }

    public function edit(Material $material): View
    {
        $teacher = $this->getTeacherProfile();
        abort_if($material->teacher_id !== $teacher->id, 403, 'Anda tidak memiliki akses ke materi ini.');

        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();
        
        $classes = collect();
        $subjects = collect();
        
        if ($activeAcademicYear && $activeSemester) {
            $teacherSubjects = TeacherSubject::with(['classroom', 'subject'])
                ->where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('semester_id', $activeSemester->id)
                ->get();
                
            $classes = $teacherSubjects->pluck('classroom')->unique('id')->values();
            $subjects = $teacherSubjects->pluck('subject')->unique('id')->values();
        }

        // If the material belongs to a class/subject not in the active semester, we should still include it in the select list to prevent validation errors on edit, or assume they can't change it to inactive ones.
        // We'll append the current classroom and subject if they aren't in the list
        if (!$classes->contains('id', $material->class_id)) {
            $classes->push($material->classroom);
        }
        if (!$subjects->contains('id', $material->subject_id)) {
            $subjects->push($material->subject);
        }

        return view('pages.guru.materials.edit', compact('material', 'classes', 'subjects', 'meetings'));
    }

    public function update(MaterialRequest $request, Material $material): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        abort_if($material->teacher_id !== $teacher->id, 403, 'Anda tidak memiliki akses ke materi ini.');

        $data = $request->validated();
        // Prevent modifying teacher_id
        unset($data['teacher_id']);

        $this->ensureMeetingMatchesMaterial($data, $teacher);

        if ($request->hasFile('file')) {
            if ($material->file_path) {
                Storage::disk('local')->delete($material->file_path);
            }
            $data['file_path'] = $request->file('file')->store('materials/pdfs', 'local');
        }

        if ($request->hasFile('video')) {
            if ($material->video_path) {
                Storage::disk('local')->delete($material->video_path);
            }
            $data['video_path'] = $request->file('video')->store('materials/videos', 'local');
        }

        $material->update($data);

        return redirect()->route('guru.materials.index')->with('success', 'Materi pembelajaran berhasil diperbarui.');
    }

    public function destroy(Material $material): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        abort_if($material->teacher_id !== $teacher->id, 403, 'Anda tidak memiliki akses ke materi ini.');

        if ($material->file_path) {
            Storage::disk('local')->delete($material->file_path);
        }

        if ($material->video_path) {
            Storage::disk('local')->delete($material->video_path);
        }

        $material->delete();

        return redirect()->route('guru.materials.index')->with('success', 'Materi pembelajaran berhasil dihapus.');
    }
    public function download(Material $material)
    {
        $teacher = $this->getTeacherProfile();
        abort_if($material->teacher_id !== $teacher->id, 403, 'Anda tidak memiliki akses ke materi ini.');
        
        $type = request()->query('type', 'file');
        
        $path = $type === 'video' ? $material->video_path : $material->file_path;
        
        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }
        
        return Storage::disk('local')->download($path);
    }
}
