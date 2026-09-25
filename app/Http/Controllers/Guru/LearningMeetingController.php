<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\LearningMeetingRequest;
use App\Models\AcademicYear;
use App\Models\LearningMeeting;
use App\Models\Semester;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LearningMeetingController extends Controller
{
    private function getTeacherProfile(): Teacher
    {
        return Teacher::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(): View
    {
        $teacher = $this->getTeacherProfile();
        $meetings = LearningMeeting::where('teacher_id', $teacher->id)
            ->with(['classroom', 'subject'])
            ->withCount(['materials', 'assessments'])
            ->orderByDesc('meeting_date')
            ->paginate(12);

        return view('pages.guru.learning-meetings.index', compact('meetings'));
    }

    public function create(): View
    {
        $teacher = $this->getTeacherProfile();

        return view('pages.guru.learning-meetings.create', [
            'classes' => $teacher->classes,
            'subjects' => $teacher->subjects,
            'academicYear' => AcademicYear::where('is_active', true)->first(),
            'semester' => Semester::where('is_active', true)->first(),
        ]);
    }

    public function store(LearningMeetingRequest $request): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        $academicYear = AcademicYear::where('is_active', true)->firstOrFail();
        $semester = Semester::where('is_active', true)->firstOrFail();
        $data = $request->validated();

        abort_if(
            ! $teacher->classes->contains('id', $data['class_id'])
            || ! $teacher->subjects->contains('id', $data['subject_id']),
            403,
            'Anda tidak memiliki penugasan pada kelas atau mata pelajaran ini.'
        );

        LearningMeeting::create([
            ...$data,
            'teacher_id' => $teacher->id,
            'academic_year_id' => $academicYear->id,
            'semester_id' => $semester->id,
        ]);

        return redirect()->route('guru.learning-meetings.index')
            ->with('success', 'Pertemuan pembelajaran berhasil dibuat. Materi dan penilaian dapat dikaitkan ke pertemuan ini.');
    }
}
