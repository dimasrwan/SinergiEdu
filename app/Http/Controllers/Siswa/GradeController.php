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
}
