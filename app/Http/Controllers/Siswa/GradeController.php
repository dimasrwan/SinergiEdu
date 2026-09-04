<?php

declare(strict_types=1);

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    private function getStudentProfile(): Student
    {
        return Student::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(Request $request): View
    {
        $student = $this->getStudentProfile();
        
        $academicYears = AcademicYear::orderByDesc('year')->get();
        $semesters = Semester::orderBy('id')->get();
        
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $selectedAcademicYearId = $request->query('academic_year_id', $activeAcademicYear?->id);
        $selectedSemesterId = $request->query('semester_id', $activeSemester?->id);

        $grades = collect();
        if ($selectedAcademicYearId && $selectedSemesterId) {
            $grades = StudentGrade::where('student_id', $student->id)
                ->where('academic_year_id', $selectedAcademicYearId)
                ->where('semester_id', $selectedSemesterId)
                ->with(['subject', 'teacher.user'])
                ->get();
        }

        return view('pages.siswa.grades.index', compact(
            'academicYears', 'semesters', 'selectedAcademicYearId', 'selectedSemesterId', 'grades'
        ));
    }

    public function show(StudentGrade $grade): View
    {
        $student = $this->getStudentProfile();
        
        abort_if($grade->student_id !== $student->id, 403, 'Anda tidak berhak melihat nilai ini.');
        
        // Ensure the student actually belongs to the class in the given academic context
        $isStudentInClass = \App\Models\StudentClass::where('student_id', $student->id)
            ->where('class_id', $grade->class_id)
            ->where('academic_year_id', $grade->academic_year_id)
            ->exists();
            
        abort_if(!$isStudentInClass, 403, 'Akses ditolak. Kelas tidak valid untuk periode ini.');

        // Validate TeacherSubject context exists
        $teacherSubjectExists = \App\Models\TeacherSubject::where('teacher_id', $grade->teacher_id)
            ->where('class_id', $grade->class_id)
            ->where('subject_id', $grade->subject_id)
            ->where('academic_year_id', $grade->academic_year_id)
            ->where('semester_id', $grade->semester_id)
            ->exists();

        abort_if(!$teacherSubjectExists, 403, 'Akses ditolak. Konteks akademik tidak valid.');

        $grade->load(['subject', 'teacher.user', 'classroom', 'academicYear', 'semester']);
        
        // Retrieve assignments matching the valid context
        $assignments = \App\Models\Assignment::where('class_id', $grade->class_id)
            ->where('subject_id', $grade->subject_id)
            ->where('teacher_id', $grade->teacher_id)
            ->with(['submissions' => function($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
            ->get();
            
        return view('pages.siswa.grades.show', compact('grade', 'assignments'));
    }
}
