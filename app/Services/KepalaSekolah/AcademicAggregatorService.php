<?php

declare(strict_types=1);

namespace App\Services\KepalaSekolah;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Semester;
use App\Models\StudentGrade;
use Illuminate\Support\Collection;

class AcademicAggregatorService
{
    private ?AcademicYear $activeYear;
    private ?Semester $activeSemester;

    public function __construct()
    {
        $this->activeYear = AcademicYear::where('is_active', true)->first();
        $this->activeSemester = Semester::where('is_active', true)->first();
    }

    public function activeYear(): ?AcademicYear
    {
        return $this->activeYear;
    }

    public function activeSemester(): ?Semester
    {
        return $this->activeSemester;
    }

    public function hasContext(): bool
    {
        return $this->activeYear !== null && $this->activeSemester !== null;
    }

    public function getSchoolAverageGrade(int $schoolId, ?int $semesterId = null): float
    {
        $semesterId = $semesterId ?? $this->activeSemester?->id;
        if (!$this->hasContext() || $semesterId === null) {
            return 0;
        }

        $grades = StudentGrade::where('semester_id', $semesterId)
            ->get();

        return $grades->isNotEmpty()
            ? round($grades->avg(fn ($g) => $g->average_score) ?? 0, 2)
            : 0;
    }

    public function getClassRankings(int $schoolId, ?int $semesterId = null): Collection
    {
        $semesterId = $semesterId ?? $this->activeSemester?->id;
        if (!$this->hasContext() || $semesterId === null) {
            return collect([]);
        }

        return Classroom::all()
            ->map(function ($class) use ($semesterId) {
                $avg = StudentGrade::where('class_id', $class->id)
                    ->where('semester_id', $semesterId)
                    ->get()
                    ->avg(fn ($g) => $g->average_score);
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'grade_level' => $class->grade_level,
                    'avg' => $avg ? round($avg, 2) : 0,
                ];
            })
            ->sortByDesc('avg')
            ->values();
    }

    public function getComponentAverages(int $schoolId, ?int $semesterId = null): array
    {
        $semesterId = $semesterId ?? $this->activeSemester?->id;
        $empty = [
            'avg_pre_test' => 0,
            'avg_assignment' => 0,
            'avg_post_test' => 0,
            'avg_character' => 0,
            'avg_memorization' => 0,
        ];

        if (!$this->hasContext() || $semesterId === null) {
            return $empty;
        }

        $grades = StudentGrade::where('semester_id', $semesterId)->get();
        if ($grades->isEmpty()) {
            return $empty;
        }

        return [
            'avg_pre_test' => round($grades->whereNotNull('pre_test_score')->avg('pre_test_score') ?? 0, 1),
            'avg_assignment' => round($grades->whereNotNull('assignment_score')->avg('assignment_score') ?? 0, 1),
            'avg_post_test' => round($grades->whereNotNull('post_test_score')->avg('post_test_score') ?? 0, 1),
            'avg_character' => round($grades->whereNotNull('character_score')->avg('character_score') ?? 0, 1),
            'avg_memorization' => round($grades->whereNotNull('memorization_score')->avg('memorization_score') ?? 0, 1),
        ];
    }

    public function getSubjectAnalysis(int $schoolId, ?int $semesterId = null): Collection
    {
        $semesterId = $semesterId ?? $this->activeSemester?->id;
        if (!$this->hasContext() || $semesterId === null) {
            return collect([]);
        }

        $subjects = \App\Models\Subject::all();
        $classes = Classroom::all();

        return $subjects->map(function ($subject) use ($semesterId, $classes) {
            $rows = StudentGrade::where('subject_id', $subject->id)
                ->where('semester_id', $semesterId)
                ->get();

            $avg = $rows->isNotEmpty()
                ? round($rows->avg(fn ($g) => $g->average_score) ?? 0, 2)
                : 0;

            $passed = $rows->filter(fn ($g) => $g->average_score >= 75)->count();
            $total = $rows->count();
            $passRate = $total > 0 ? round(($passed / $total) * 100, 1) : 0;

            $perClass = $classes->map(function ($class) use ($subject, $semesterId) {
                $rows = StudentGrade::where('class_id', $class->id)
                    ->where('subject_id', $subject->id)
                    ->where('semester_id', $semesterId)
                    ->get();
                return [
                    'class_id' => $class->id,
                    'class_name' => $class->name,
                    'avg' => $rows->isNotEmpty() ? round($rows->avg(fn ($g) => $g->average_score) ?? 0, 2) : 0,
                    'avg_character' => round($rows->avg('character_score') ?? 0, 1),
                    'avg_memorization' => round($rows->avg('memorization_score') ?? 0, 1),
                ];
            });

            return [
                'id' => $subject->id,
                'name' => $subject->name,
                'code' => $subject->code,
                'avg' => $avg,
                'pass_rate' => $passRate,
                'passed' => $passed,
                'total' => $total,
                'avg_character' => round($rows->avg('character_score') ?? 0, 1),
                'avg_memorization' => round($rows->avg('memorization_score') ?? 0, 1),
                'per_class' => $perClass,
            ];
        })->values();
    }

    public function getRekapList(int $schoolId, ?int $academicYearId = null, ?int $semesterId = null, ?int $classId = null, ?int $subjectId = null): Collection
    {
        $semesterId = $semesterId ?? $this->activeSemester?->id;
        if ($semesterId === null) {
            return collect([]);
        }

        $query = StudentGrade::with(['student.user', 'classroom', 'subject'])
            ->where('semester_id', $semesterId);

        if ($classId) {
            $query->where('class_id', $classId);
        }
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        return $query->get()->groupBy('student_id')->map(function ($grades, $studentId) {
            $first = $grades->first();
            return [
                'student_id' => $studentId,
                'name' => $first->student->user->name,
                'nisn' => $first->student->nisn,
                'nis' => $first->student->nis,
                'class_name' => $first->classroom->name ?? '-',
                'avg' => round($grades->avg(fn ($g) => $g->average_score) ?? 0, 2),
                'avg_pre_test' => round($grades->avg('pre_test_score') ?? 0, 1),
                'avg_assignment' => round($grades->avg('assignment_score') ?? 0, 1),
                'avg_post_test' => round($grades->avg('post_test_score') ?? 0, 1),
                'avg_character' => round($grades->avg('character_score') ?? 0, 1),
                'avg_memorization' => round($grades->avg('memorization_score') ?? 0, 1),
                'subjects' => $grades->count(),
            ];
        })->values()->sortByDesc('avg')->values();
    }
}
