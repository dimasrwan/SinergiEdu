<?php

declare(strict_types=1);

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Siswa\ReflectionRequest;
use App\Models\AcademicYear;
use App\Models\LearningMeeting;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentReflection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReflectionController extends Controller
{
    private function getStudentProfile(): Student
    {
        return Student::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(Request $request): View
    {
        $student = $this->getStudentProfile();
        $classroom = $student->activeClassroom();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $meetings = collect();
        $reflections = collect();

        if ($classroom && $activeYear && $activeSemester) {
            $meetings = LearningMeeting::where('class_id', $classroom->id)
                ->where('academic_year_id', $activeYear->id)
                ->where('semester_id', $activeSemester->id)
                ->with(['subject'])
                ->orderBy('meeting_number', 'asc')
                ->get();

            $reflections = StudentReflection::where('student_id', $student->id)
                ->whereHas('learningMeeting', function ($q) use ($classroom, $activeYear, $activeSemester) {
                    $q->where('class_id', $classroom->id)
                      ->where('academic_year_id', $activeYear->id)
                      ->where('semester_id', $activeSemester->id);
                })
                ->with(['learningMeeting.subject'])
                ->get()
                ->keyBy('learning_meeting_id');
        }

        $selectedMeetingId = $request->query('meeting_id');

        return view('pages.siswa.reflections.index', compact(
            'classroom',
            'meetings',
            'reflections',
            'selectedMeetingId',
            'activeYear',
            'activeSemester'
        ));
    }

    public function store(ReflectionRequest $request): RedirectResponse
    {
        $student = $this->getStudentProfile();
        $classroom = $student->activeClassroom();

        $meeting = LearningMeeting::findOrFail($request->learning_meeting_id);

        // Security Validation: Ensure meeting belongs to student's active classroom
        abort_if(!$classroom || $meeting->class_id !== $classroom->id, 403, 'Akses ditolak.');

        StudentReflection::updateOrCreate(
            [
                'student_id' => $student->id,
                'learning_meeting_id' => $meeting->id,
            ],
            [
                'content' => $request->content,
            ]
        );

        return redirect()->route('siswa.reflections.index')
            ->with('success', 'Refleksi pembelajaran berhasil disimpan.');
    }
}
