<?php

declare(strict_types=1);

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Student;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    private function getStudentProfile(): Student
    {
        return Student::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(): View
    {
        $student = $this->getStudentProfile();

        $feedbacks = Feedback::where('student_id', $student->id)
            ->with(['teacher.user', 'subject'])
            ->latest()
            ->paginate(10);

        return view('pages.siswa.feedbacks.index', compact('feedbacks'));
    }

    public function show(Feedback $feedback): View
    {
        $student = $this->getStudentProfile();
        abort_if($feedback->student_id !== $student->id, 403, 'Akses ditolak.');

        $feedback->load(['teacher.user', 'subject']);

        return view('pages.siswa.feedbacks.show', compact('feedback'));
    }
}
