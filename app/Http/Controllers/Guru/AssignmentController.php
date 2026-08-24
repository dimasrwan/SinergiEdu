<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\AssignmentRequest;
use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\Semester;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AssignmentController extends Controller
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

        $query = Assignment::where('teacher_id', $teacher->id)
            ->with(['classroom', 'subject'])
            ->withCount('submissions');
            
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

        $assignments = $query->latest()->paginate(10);

        return view('pages.guru.assignments.index', compact('assignments'));
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
                
            $classes = $teacherSubjects->pluck('classroom')->unique('id')->values();
            $subjects = $teacherSubjects->pluck('subject')->unique('id')->values();
        }

        return view('pages.guru.assignments.create', compact('classes', 'subjects'));
    }

    public function store(AssignmentRequest $request): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        $data = $request->validated();
        
        // Validasi Context (Kombinasi Class dan Subject di TeacherSubject aktif)
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();
        
        if ($activeAcademicYear && $activeSemester) {
            $isValidContext = TeacherSubject::where('teacher_id', $teacher->id)
                ->where('class_id', $data['class_id'])
                ->where('subject_id', $data['subject_id'])
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('semester_id', $activeSemester->id)
                ->exists();
                
            if (!$isValidContext) {
                return back()->withInput()->withErrors(['class_id' => 'Kombinasi kelas dan mata pelajaran tidak sah untuk penugasan Anda.']);
            }
        }
        
        $data['teacher_id'] = $teacher->id;

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('assignments/attachments', 'local');
        }

        Assignment::create($data);

        return redirect()->route('guru.assignments.index')->with('success', 'Tugas baru berhasil dibuat.');
    }

    public function show(Assignment $assignment): View
    {
        $teacher = $this->getTeacherProfile();
        abort_if($assignment->teacher_id !== $teacher->id, 403, 'Akses ditolak.');

        $assignment->load(['classroom', 'subject']);
        $assignment->loadCount('submissions');

        return view('pages.guru.assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment): View
    {
        $teacher = $this->getTeacherProfile();
        abort_if($assignment->teacher_id !== $teacher->id, 403, 'Akses ditolak.');

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

        if (!$classes->contains('id', $assignment->class_id)) {
            $classes->push($assignment->classroom);
        }
        if (!$subjects->contains('id', $assignment->subject_id)) {
            $subjects->push($assignment->subject);
        }

        return view('pages.guru.assignments.edit', compact('assignment', 'classes', 'subjects'));
    }

    public function update(AssignmentRequest $request, Assignment $assignment): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        abort_if($assignment->teacher_id !== $teacher->id, 403, 'Akses ditolak.');

        $data = $request->validated();
        
        // Validasi Context (Kombinasi Class dan Subject di TeacherSubject aktif)
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();
        
        if ($activeAcademicYear && $activeSemester) {
            $isValidContext = TeacherSubject::where('teacher_id', $teacher->id)
                ->where('class_id', $data['class_id'])
                ->where('subject_id', $data['subject_id'])
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('semester_id', $activeSemester->id)
                ->exists();
                
            // Allow if it matches the current assignment's context, even if inactive
            if (!$isValidContext && ($data['class_id'] != $assignment->class_id || $data['subject_id'] != $assignment->subject_id)) {
                return back()->withInput()->withErrors(['class_id' => 'Kombinasi kelas dan mata pelajaran tidak sah untuk penugasan Anda.']);
            }
        }

        unset($data['teacher_id']); // Prevent modification

        if ($request->hasFile('attachment')) {
            if ($assignment->attachment_path) {
                Storage::disk('local')->delete($assignment->attachment_path);
            }
            $data['attachment_path'] = $request->file('attachment')->store('assignments/attachments', 'local');
        }

        $assignment->update($data);

        return redirect()->route('guru.assignments.index')->with('success', 'Data tugas berhasil diperbarui.');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        abort_if($assignment->teacher_id !== $teacher->id, 403, 'Akses ditolak.');

        if ($assignment->submissions()->exists()) {
            return back()->with('error', 'Tugas tidak dapat dihapus karena sudah ada siswa yang mengumpulkan jawaban.');
        }

        if ($assignment->attachment_path) {
            Storage::disk('local')->delete($assignment->attachment_path);
        }

        $assignment->delete();

        return redirect()->route('guru.assignments.index')->with('success', 'Tugas berhasil dihapus.');
    }
    
    public function download(Assignment $assignment)
    {
        $teacher = $this->getTeacherProfile();
        abort_if($assignment->teacher_id !== $teacher->id, 403, 'Anda tidak memiliki akses ke tugas ini.');
        
        $path = $assignment->attachment_path;
        
        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }
        
        return Storage::disk('local')->download($path);
    }
}
