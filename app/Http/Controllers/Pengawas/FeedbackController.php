<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\StudentGrade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    /**
     * Tampilkan daftar feedback yang diberikan Pengawas ke siswa.
     */
    public function index(): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        // Dapatkan feedback dari pengawas
        $feedbacks = Student::query()
            ->with(['user', 'studentGrades' => function ($q) use ($activeYear, $activeSemester) {
                $q->where('academic_year_id', $activeYear?->id)
                  ->where('semester_id', $activeSemester?->id)
                  ->whereNotNull('supervisor_feedback');
            }])
            ->paginate(15);

        return view('pages.pengawas.feedback.index', compact(
            'feedbacks', 'activeYear', 'activeSemester'
        ));
    }

    /**
     * Tampilkan form untuk memberikan feedback ke siswa.
     */
    public function create(Request $request): View
    {
        $student = Student::findOrFail($request->student_id);
        
        // Cek akses
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $grades = $student->studentGrades()
            ->where('academic_year_id', $activeYear?->id)
            ->where('semester_id', $activeSemester?->id)
            ->with('subject', 'teacher.user')
            ->get();

        return view('pages.pengawas.feedback.create', compact(
            'student', 'grades', 'activeYear', 'activeSemester'
        ));
    }

    /**
     * Simpan feedback ke siswa.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'feedback_text' => 'required|string|min:10|max:1000',
            'action_plan' => 'nullable|string|max:500',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $student = Student::findOrFail($validated['student_id']);

        // Cek akses
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        // Simpan feedback ke semua grade siswa semester ini
        $student->studentGrades()
            ->where('academic_year_id', $activeYear?->id)
            ->where('semester_id', $activeSemester?->id)
            ->update([
                'supervisor_feedback' => $validated['feedback_text'],
                'supervisor_action_plan' => $validated['action_plan'],
                'supervisor_priority' => $validated['priority'] ?? 'medium',
                'supervisor_id' => auth()->id(),
            ]);

        return redirect()
            ->route('pengawas.students.show', $student->id)
            ->with('success', 'Feedback berhasil diberikan kepada siswa');
    }

    /**
     * Tampilkan feedback spesifik untuk diedit.
     */
    public function edit(Student $student): View
    {
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $grades = $student->studentGrades()
            ->where('academic_year_id', $activeYear?->id)
            ->where('semester_id', $activeSemester?->id)
            ->with('subject')
            ->get();

        $lastFeedback = $grades->first();

        return view('pages.pengawas.feedback.edit', compact(
            'student', 'lastFeedback', 'activeYear', 'activeSemester'
        ));
    }

    /**
     * Update feedback siswa.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'feedback_text' => 'required|string|min:10|max:1000',
            'action_plan' => 'nullable|string|max:500',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $student->studentGrades()
            ->where('academic_year_id', $activeYear?->id)
            ->where('semester_id', $activeSemester?->id)
            ->where('supervisor_id', auth()->id())
            ->update([
                'supervisor_feedback' => $validated['feedback_text'],
                'supervisor_action_plan' => $validated['action_plan'],
                'supervisor_priority' => $validated['priority'] ?? 'medium',
            ]);

        return redirect()
            ->route('pengawas.students.show', $student->id)
            ->with('success', 'Feedback berhasil diperbarui');
    }

    /**
     * Arsipkan feedback siswa.
     */
    public function archive(Request $request, Student $student): RedirectResponse
    {
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $student->studentGrades()
            ->where('academic_year_id', $activeYear?->id)
            ->where('semester_id', $activeSemester?->id)
            ->where('supervisor_id', auth()->id())
            ->update(['is_archived' => true]);

        return back()->with('success', 'Feedback berhasil diarsipkan');
    }

    /**
     * Batalkan arsip feedback siswa.
     */
    public function unarchive(Request $request, Student $student): RedirectResponse
    {
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $student->studentGrades()
            ->where('academic_year_id', $activeYear?->id)
            ->where('semester_id', $activeSemester?->id)
            ->where('supervisor_id', auth()->id())
            ->update(['is_archived' => false]);

        return back()->with('success', 'Arsip feedback berhasil dibatalkan');
    }

    /**
     * Tampilkan daftar feedback yang diarsipkan.
     */
    public function archived(): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $archived = StudentGrade::query()
            ->with(['student.user', 'subject', 'teacher'])
            ->where('supervisor_id', auth()->id())
            ->where('is_archived', true)
            ->where('academic_year_id', $activeYear?->id)
            ->where('semester_id', $activeSemester?->id)
            ->latest()
            ->paginate(15);

        return view('pages.pengawas.feedback.archived', compact(
            'archived', 'activeYear', 'activeSemester'
        ));
    }
}
