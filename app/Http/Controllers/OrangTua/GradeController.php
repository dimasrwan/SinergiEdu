<?php

declare(strict_types=1);

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\StudentParent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    private function getParentProfile(): StudentParent
    {
        return StudentParent::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(Request $request): View
    {
        $parent = $this->getParentProfile();
        $children = $parent->students;
        
        $academicYears = AcademicYear::orderByDesc('year')->get();
        $semesters = Semester::orderBy('id')->get();
        
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $selectedAcademicYearId = $request->query('academic_year_id', $activeAcademicYear?->id);
        $selectedSemesterId = $request->query('semester_id', $activeSemester?->id);

        $childrenGrades = [];
        
        if ($selectedAcademicYearId && $selectedSemesterId) {
            foreach ($children as $child) {
                $grades = $child->grades()
                    ->where('academic_year_id', $selectedAcademicYearId)
                    ->where('semester_id', $selectedSemesterId)
                    ->with(['subject', 'teacher.user'])
                    ->get();
                    
                $childrenGrades[$child->id] = $grades;
            }
        }

        return view('pages.orangtua.grades.index', compact(
            'children', 'academicYears', 'semesters', 'selectedAcademicYearId', 'selectedSemesterId', 'childrenGrades'
        ));
    }
}
