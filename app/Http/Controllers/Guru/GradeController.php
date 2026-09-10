<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\LearningMeeting;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentAssessment;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GradeController extends Controller
{
    private function getTeacherProfile(): Teacher
    {
        return Teacher::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(Request $request): View
    {
        $teacher = $this->getTeacherProfile();
        
        $academicYear = AcademicYear::where('is_active', true)->first();
        $semester = Semester::where('is_active', true)->first();

        $classes = $teacher->classes;
        $subjects = $teacher->subjects;

        $selectedClassId = $request->query('class_id');
        $selectedSubjectId = $request->query('subject_id');
        $selectedMeetingId = $request->query('meeting_id');

        $students = collect();
        $grades = collect();
        $meetings = collect();

        if ($selectedClassId && $selectedSubjectId && $academicYear && $semester) {
            // Validasi kepemilikan
            abort_if(!$classes->contains('id', $selectedClassId) || !$subjects->contains('id', $selectedSubjectId), 403, 'Akses ditolak.');
            
            $meetings = LearningMeeting::where('teacher_id', $teacher->id)
                ->where('class_id', $selectedClassId)
                ->where('subject_id', $selectedSubjectId)
                ->where('academic_year_id', $academicYear->id)
                ->where('semester_id', $semester->id)
                ->orderBy('meeting_number')
                ->get();

            $students = Student::whereHas('classes', function ($q) use ($selectedClassId, $academicYear) {
                    $q->where('class_id', $selectedClassId)
                      ->where('academic_year_id', $academicYear->id);
                })
                ->with('user')
                ->orderBy(function ($query) {
                    $query->select('name')
                        ->from('users')
                        ->whereColumn('users.id', 'students.user_id')
                        ->limit(1);
                })
                ->get();

            if ($selectedMeetingId) {
                $meeting = $meetings->firstWhere('id', (int) $selectedMeetingId);
                abort_unless($meeting, 403, 'Pertemuan tidak dapat diakses.');

                $grades = StudentAssessment::where('learning_meeting_id', $meeting->id)
                    ->get()
                    ->keyBy('student_id');
            }
        }

        return view('pages.guru.grades.index', compact(
            'classes', 'subjects', 'academicYear', 'semester', 
            'selectedClassId', 'selectedSubjectId', 'selectedMeetingId', 'meetings', 'students', 'grades'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'meeting_id' => 'required|exists:learning_meetings,id',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.pre_test_score' => 'nullable|integer|min:0|max:100',
            'grades.*.assignment_score' => 'nullable|integer|min:0|max:100',
            'grades.*.post_test_score' => 'nullable|integer|min:0|max:100',
            'grades.*.character_score' => 'nullable|integer|min:0|max:100',
            'grades.*.memorization_score' => 'nullable|integer|min:0|max:100',
            'grades.*.memorization_juz' => 'nullable|string|max:30',
            'grades.*.memorization_ayat' => 'nullable|string|max:100',
            'grades.*.notes' => 'nullable|string|max:1000',
        ]);

        $teacher = $this->getTeacherProfile();
        $academicYear = AcademicYear::where('is_active', true)->firstOrFail();
        $semester = Semester::where('is_active', true)->firstOrFail();

        $classId = $request->input('class_id');
        $subjectId = $request->input('subject_id');
        $meetingId = $request->integer('meeting_id');

        abort_if(!$teacher->classes->contains('id', $classId) || !$teacher->subjects->contains('id', $subjectId), 403, 'Akses ditolak.');

        $meeting = LearningMeeting::whereKey($meetingId)
            ->where('teacher_id', $teacher->id)
            ->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('academic_year_id', $academicYear->id)
            ->where('semester_id', $semester->id)
            ->firstOrFail();

        foreach ($request->input('grades') as $gradeData) {
            StudentAssessment::updateOrCreate(
                ['learning_meeting_id' => $meeting->id, 'student_id' => $gradeData['student_id']],
                [
                    'pre_test_score' => $gradeData['pre_test_score'],
                    'assignment_score' => $gradeData['assignment_score'],
                    'post_test_score' => $gradeData['post_test_score'],
                    'character_score' => $gradeData['character_score'],
                    'memorization_score' => $gradeData['memorization_score'],
                    'memorization_juz' => $gradeData['memorization_juz'],
                    'memorization_ayat' => $gradeData['memorization_ayat'],
                    'notes' => $gradeData['notes'],
                ]
            );
        }

        return redirect()->route('guru.grades.index', [
            'class_id' => $classId,
            'subject_id' => $subjectId,
            'meeting_id' => $meeting->id,
        ])->with('success', 'Penilaian pertemuan berhasil disimpan.');
    }
}
