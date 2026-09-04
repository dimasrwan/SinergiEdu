<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\MaterialRequest;
use App\Models\LearningMeeting;
use App\Models\Material;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MaterialController extends Controller
{
    private function getTeacherProfile(): Teacher
    {
        return Teacher::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(): View
    {
        $teacher = $this->getTeacherProfile();
        $materials = Material::where('teacher_id', $teacher->id)
            ->with(['classroom', 'subject'])
            ->latest()
            ->paginate(10);

        return view('pages.guru.materials.index', compact('materials'));
    }

    public function create(): View
    {
        $teacher = $this->getTeacherProfile();
        // Ambil kelas dan mapel yang ditugaskan ke guru ini
        $classes = $teacher->classes;
        $subjects = $teacher->subjects;
        $meetings = LearningMeeting::where('teacher_id', $teacher->id)
            ->with(['classroom', 'subject'])
            ->orderByDesc('meeting_date')
            ->get();

        return view('pages.guru.materials.create', compact('classes', 'subjects', 'meetings'));
    }

    public function store(MaterialRequest $request): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        $data = $request->validated();
        $data['teacher_id'] = $teacher->id;

        $this->ensureMeetingMatchesMaterial($data, $teacher);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('materials/pdfs', 'public');
        }

        if ($request->hasFile('video')) {
            $data['video_path'] = $request->file('video')->store('materials/videos', 'public');
        }

        Material::create($data);

        return redirect()->route('guru.materials.index')->with('success', 'Materi pembelajaran berhasil diunggah.');
    }

    public function edit(Material $material): View
    {
        $teacher = $this->getTeacherProfile();
        abort_if($material->teacher_id !== $teacher->id, 403, 'Anda tidak memiliki akses ke materi ini.');

        $classes = $teacher->classes;
        $subjects = $teacher->subjects;
        $meetings = LearningMeeting::where('teacher_id', $teacher->id)
            ->with(['classroom', 'subject'])
            ->orderByDesc('meeting_date')
            ->get();

        return view('pages.guru.materials.edit', compact('material', 'classes', 'subjects', 'meetings'));
    }

    public function update(MaterialRequest $request, Material $material): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        abort_if($material->teacher_id !== $teacher->id, 403, 'Anda tidak memiliki akses ke materi ini.');

        $data = $request->validated();

        $this->ensureMeetingMatchesMaterial($data, $teacher);

        if ($request->hasFile('file')) {
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }
            $data['file_path'] = $request->file('file')->store('materials/pdfs', 'public');
        }

        if ($request->hasFile('video')) {
            if ($material->video_path) {
                Storage::disk('public')->delete($material->video_path);
            }
            $data['video_path'] = $request->file('video')->store('materials/videos', 'public');
        }

        $material->update($data);

        return redirect()->route('guru.materials.index')->with('success', 'Materi pembelajaran berhasil diperbarui.');
    }

    public function destroy(Material $material): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        abort_if($material->teacher_id !== $teacher->id, 403, 'Anda tidak memiliki akses ke materi ini.');

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        if ($material->video_path) {
            Storage::disk('public')->delete($material->video_path);
        }

        $material->delete();

        return redirect()->route('guru.materials.index')->with('success', 'Materi pembelajaran berhasil dihapus.');
    }

    private function ensureMeetingMatchesMaterial(array $data, Teacher $teacher): void
    {
        if (empty($data['learning_meeting_id'])) {
            return;
        }

        $matches = LearningMeeting::whereKey($data['learning_meeting_id'])
            ->where('teacher_id', $teacher->id)
            ->where('class_id', $data['class_id'])
            ->where('subject_id', $data['subject_id'])
            ->exists();

        abort_unless($matches, 422, 'Pertemuan pembelajaran harus sesuai dengan guru, kelas, dan mata pelajaran materi.');
    }
}
