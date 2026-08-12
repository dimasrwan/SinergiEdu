<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\AssignmentRequest;
use App\Models\Assignment;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    private function getTeacherProfile(): Teacher
    {
        return Teacher::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(): View
    {
        $teacher = $this->getTeacherProfile();
        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with(['classroom', 'subject'])
            ->withCount('submissions')
            ->latest()
            ->paginate(10);

        return view('pages.guru.assignments.index', compact('assignments'));
    }

    public function create(): View
    {
        $teacher = $this->getTeacherProfile();
        $classes = $teacher->classes;
        $subjects = $teacher->subjects;

        return view('pages.guru.assignments.create', compact('classes', 'subjects'));
    }

    public function store(AssignmentRequest $request): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        $data = $request->validated();
        $data['teacher_id'] = $teacher->id;

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('assignments/attachments', 'public');
        }

        Assignment::create($data);

        return redirect()->route('guru.assignments.index')->with('success', 'Tugas baru berhasil dibuat.');
    }

    public function show(Assignment $assignment): View
    {
        $teacher = $this->getTeacherProfile();
        abort_if($assignment->teacher_id !== $teacher->id, 403, 'Akses ditolak.');

        $assignment->load(['classroom', 'subject', 'submissions.student.user']);

        return view('pages.guru.assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment): View
    {
        $teacher = $this->getTeacherProfile();
        abort_if($assignment->teacher_id !== $teacher->id, 403, 'Akses ditolak.');

        $classes = $teacher->classes;
        $subjects = $teacher->subjects;

        return view('pages.guru.assignments.edit', compact('assignment', 'classes', 'subjects'));
    }

    public function update(AssignmentRequest $request, Assignment $assignment): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        abort_if($assignment->teacher_id !== $teacher->id, 403, 'Akses ditolak.');

        $data = $request->validated();

        if ($request->hasFile('attachment')) {
            if ($assignment->attachment_path) {
                Storage::disk('public')->delete($assignment->attachment_path);
            }
            $data['attachment_path'] = $request->file('attachment')->store('assignments/attachments', 'public');
        }

        $assignment->update($data);

        return redirect()->route('guru.assignments.index')->with('success', 'Data tugas berhasil diperbarui.');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        abort_if($assignment->teacher_id !== $teacher->id, 403, 'Akses ditolak.');

        if ($assignment->attachment_path) {
            Storage::disk('public')->delete($assignment->attachment_path);
        }

        $assignment->delete();

        return redirect()->route('guru.assignments.index')->with('success', 'Tugas berhasil dihapus.');
    }
}
