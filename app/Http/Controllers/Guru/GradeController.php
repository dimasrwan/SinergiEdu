<?php

declare(strict_types=1);

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

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

        $students = collect();
        $grades = collect();

        if ($selectedClassId && $selectedSubjectId && $academicYear && $semester) {
            // Validasi kepemilikan
            abort_if(!$classes->contains('id', $selectedClassId) || !$subjects->contains('id', $selectedSubjectId), 403, 'Akses ditolak.');
            
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

            $grades = StudentGrade::where('class_id', $selectedClassId)
                ->where('subject_id', $selectedSubjectId)
                ->where('academic_year_id', $academicYear->id)
                ->where('semester_id', $semester->id)
                ->get()
                ->keyBy('student_id');
        }

        return view('pages.guru.grades.index', compact(
            'classes', 'subjects', 'academicYear', 'semester', 
            'selectedClassId', 'selectedSubjectId', 'students', 'grades'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.pre_test_score' => 'nullable|integer|min:0|max:100',
            'grades.*.assignment_score' => 'nullable|integer|min:0|max:100',
            'grades.*.post_test_score' => 'nullable|integer|min:0|max:100',
            'grades.*.character_score' => 'nullable|integer|min:0|max:100',
            'grades.*.memorization_score' => 'nullable|integer|min:0|max:100',
        ]);

        $teacher = $this->getTeacherProfile();
        $academicYear = AcademicYear::where('is_active', true)->firstOrFail();
        $semester = Semester::where('is_active', true)->firstOrFail();

        $classId = $request->input('class_id');
        $subjectId = $request->input('subject_id');

        abort_if(!$teacher->classes->contains('id', $classId) || !$teacher->subjects->contains('id', $subjectId), 403, 'Akses ditolak.');

        DB::transaction(function () use ($request, $teacher, $academicYear, $semester, $classId, $subjectId) {
            foreach ($request->input('grades') as $gradeData) {
                StudentGrade::updateOrCreate(
                    [
                        'student_id' => $gradeData['student_id'],
                        'subject_id' => $subjectId,
                        'academic_year_id' => $academicYear->id,
                        'semester_id' => $semester->id,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'class_id' => $classId,
                        'pre_test_score' => $gradeData['pre_test_score'],
                        'assignment_score' => $gradeData['assignment_score'],
                        'post_test_score' => $gradeData['post_test_score'],
                        'character_score' => $gradeData['character_score'],
                        'memorization_score' => $gradeData['memorization_score'],
                    ]
                );
            }
        });

        return redirect()->route('guru.grades.index', [
            'class_id' => $classId,
            'subject_id' => $subjectId,
        ])->with('success', 'Nilai siswa berhasil disimpan.');
    }
}
