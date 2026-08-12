<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\FeedbackRequest;
use App\Models\AcademicYear;
use App\Models\Feedback;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    private function getTeacherProfile(): Teacher
    {
        return Teacher::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(): View
    {
        $teacher = $this->getTeacherProfile();

        $feedbacks = Feedback::where('teacher_id', $teacher->id)
            ->with(['student.user', 'subject'])
            ->latest()
            ->paginate(10);

        return view('pages.guru.feedbacks.index', compact('feedbacks'));
    }

    public function create(): View
    {
        $teacher = $this->getTeacherProfile();
        $academicYear = AcademicYear::where('is_active', true)->first();

        // Ambil siswa dari kelas yang diajar guru ini
        $students = collect();
        if ($academicYear) {
            $classIds = $teacher->classes->pluck('id');
            $students = Student::whereHas('classes', function ($q) use ($classIds, $academicYear) {
                $q->whereIn('class_id', $classIds)
                  ->where('academic_year_id', $academicYear->id);
            })->with('user')->get()->sortBy('user.name');
        }

        $subjects = $teacher->subjects;

        return view('pages.guru.feedbacks.create', compact('students', 'subjects'));
    }

    public function store(FeedbackRequest $request): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        $data = $request->validated();
        $data['teacher_id'] = $teacher->id;

        Feedback::create($data);

        return redirect()->route('guru.feedbacks.index')->with('success', 'Feedback berhasil dikirim.');
    }

    public function show(Feedback $feedback): View
    {
        $teacher = $this->getTeacherProfile();
        abort_if($feedback->teacher_id !== $teacher->id, 403, 'Akses ditolak.');

        $feedback->load(['student.user', 'subject']);

        return view('pages.guru.feedbacks.show', compact('feedback'));
    }

    public function edit(Feedback $feedback): View
    {
        $teacher = $this->getTeacherProfile();
        abort_if($feedback->teacher_id !== $teacher->id, 403, 'Akses ditolak.');

        $academicYear = AcademicYear::where('is_active', true)->first();
        $students = collect();
        if ($academicYear) {
            $classIds = $teacher->classes->pluck('id');
            $students = Student::whereHas('classes', function ($q) use ($classIds, $academicYear) {
                $q->whereIn('class_id', $classIds)
                  ->where('academic_year_id', $academicYear->id);
            })->with('user')->get()->sortBy('user.name');
        }

        $subjects = $teacher->subjects;

        return view('pages.guru.feedbacks.edit', compact('feedback', 'students', 'subjects'));
    }

    public function update(FeedbackRequest $request, Feedback $feedback): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        abort_if($feedback->teacher_id !== $teacher->id, 403, 'Akses ditolak.');

        $feedback->update($request->validated());

        return redirect()->route('guru.feedbacks.index')->with('success', 'Feedback berhasil diperbarui.');
    }

    public function destroy(Feedback $feedback): RedirectResponse
    {
        $teacher = $this->getTeacherProfile();
        abort_if($feedback->teacher_id !== $teacher->id, 403, 'Akses ditolak.');

        $feedback->delete();

        return redirect()->route('guru.feedbacks.index')->with('success', 'Feedback berhasil dihapus.');
    }
}
