<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Feedback;
use App\Models\PerformanceReport;
use App\Models\Semester;
use App\Models\StudentGrade;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerformanceReportController extends Controller
{
    public function index(): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        // 1. Written reports
        $reports = PerformanceReport::where('pengawas_user_id', auth()->id())
            ->with(['teacher.user', 'classroom'])
            ->latest()
            ->paginate(10);

        // 2. Class Average Grades
        $classRankings = [];
        if ($activeYear && $activeSemester) {
            $classRankings = Classroom::all()->map(function ($class) use ($activeYear, $activeSemester) {
                $avg = StudentGrade::where('class_id', $class->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester_id', $activeSemester->id)
                    ->get()
                    ->avg(fn($g) => $g->average_score);
                return [
                    'name' => $class->name,
                    'avg' => $avg ? round($avg, 2) : 0
                ];
            })->sortByDesc('avg')->values();
        }

        // 3. Teacher Metrics
        $teacherMetrics = Teacher::with('user')->get()->map(function ($teacher) {
            $grades = StudentGrade::where('teacher_id', $teacher->id)->get();
            $avgScore = $grades->isNotEmpty() ? round($grades->avg(fn($g) => $g->average_score) ?? 0, 2) : 0;
            $assignmentsCount = Assignment::where('teacher_id', $teacher->id)->count();
            $feedbacksCount = Feedback::where('teacher_id', $teacher->id)->count();

            return [
                'id' => $teacher->id,
                'name' => $teacher->user?->name ?? 'Guru',
                'nip' => $teacher->nip ?? '-',
                'avg_score' => $avgScore,
                'assignments_count' => $assignmentsCount,
                'feedbacks_count' => $feedbacksCount,
            ];
        })->sortByDesc('avg_score')->values();

        return view('pages.pengawas.reports.index', compact('reports', 'classRankings', 'teacherMetrics', 'activeYear', 'activeSemester'));
    }

    public function create(): View
    {
        $teachers = Teacher::with('user')->get();
        $classrooms = Classroom::orderBy('name')->get();

        return view('pages.pengawas.reports.create', compact('teachers', 'classrooms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
            'class_id' => 'nullable|exists:classes,id',
            'content' => 'required|string|min:10',
            'recommendations' => 'nullable|string',
        ]);

        $data['pengawas_user_id'] = auth()->id();

        PerformanceReport::create($data);

        return redirect()->route('pengawas.reports.index')
            ->with('success', 'Laporan kinerja berhasil disimpan.');
    }

    public function show(PerformanceReport $report): View
    {
        abort_if($report->pengawas_user_id !== auth()->id(), 403);
        $report->load(['teacher.user', 'classroom', 'pengawas']);

        return view('pages.pengawas.reports.show', compact('report'));
    }

    public function edit(PerformanceReport $report): View
    {
        abort_if($report->pengawas_user_id !== auth()->id(), 403);

        $teachers = Teacher::with('user')->get();
        $classrooms = Classroom::orderBy('name')->get();

        return view('pages.pengawas.reports.edit', compact('report', 'teachers', 'classrooms'));
    }

    public function update(Request $request, PerformanceReport $report): RedirectResponse
    {
        abort_if($report->pengawas_user_id !== auth()->id(), 403);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
            'class_id' => 'nullable|exists:classes,id',
            'content' => 'required|string|min:10',
            'recommendations' => 'nullable|string',
        ]);

        $report->update($data);

        return redirect()->route('pengawas.reports.index')
            ->with('success', 'Laporan kinerja berhasil diperbarui.');
    }

    public function destroy(PerformanceReport $report): RedirectResponse
    {
        abort_if($report->pengawas_user_id !== auth()->id(), 403);

        $report->delete();

        return redirect()->route('pengawas.reports.index')
            ->with('success', 'Laporan kinerja berhasil dihapus.');
    }
}
