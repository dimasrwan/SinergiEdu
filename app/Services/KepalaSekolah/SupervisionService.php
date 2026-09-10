<?php

declare(strict_types=1);

namespace App\Services\KepalaSekolah;

use App\Models\Feedback;
use App\Models\Material;
use App\Models\TeacherSubject;
use App\Models\StudentGrade;
use Illuminate\Support\Collection;

class SupervisionService
{
    public function getGradingStatus(): Collection
    {
        $assignments = TeacherSubject::with(['teacher.user', 'classroom', 'subject'])
            ->get();

        return $assignments->map(function ($ts) {
            $hasGrade = StudentGrade::where('teacher_id', $ts->teacher_id)
                ->where('class_id', $ts->class_id)
                ->where('subject_id', $ts->subject_id)
                ->exists();

            return (object) [
                'teacher_id' => $ts->teacher_id,
                'teacher_name' => $ts->teacher->user->name,
                'class_id' => $ts->class_id,
                'class_name' => $ts->classroom->name,
                'subject_id' => $ts->subject_id,
                'subject_name' => $ts->subject->name,
                'status' => $hasGrade ? 'completed' : 'pending',
            ];
        });
    }

    public function getTeacherReports(): Collection
    {
        $teachers = \App\Models\Teacher::with(['user'])->get();

        return $teachers->map(function ($teacher) {
            $assignments = TeacherSubject::where('teacher_id', $teacher->id)->get();

            $materialsCount = Material::where('teacher_id', $teacher->id)->count();
            $feedbacksCount = Feedback::where('teacher_id', $teacher->id)
                ->whereNull('sender_id')
                ->count();
            $gradedCount = 0;
            $totalAssignments = $assignments->count();

            foreach ($assignments as $ts) {
                if (StudentGrade::where('teacher_id', $teacher->id)
                    ->where('class_id', $ts->class_id)
                    ->where('subject_id', $ts->subject_id)
                    ->exists()) {
                    $gradedCount++;
                }
            }

            $gradingRatio = $totalAssignments > 0
                ? round(($gradedCount / $totalAssignments) * 100, 1)
                : 0;

            $score = round(
                ($gradingRatio * 0.30) +
                (min($materialsCount, 20) / 20 * 100 * 0.25) +
                (min($assignments->count(), 15) / 15 * 100 * 0.20) +
                (min($feedbacksCount, 10) / 10 * 100 * 0.15) +
                ($gradingRatio * 0.10)
            , 1);

            return (object) [
                'id' => $teacher->id,
                'name' => $teacher->user->name,
                'nip' => $teacher->nip,
                'subjects' => $assignments->pluck('subject.name')->unique()->implode(', '),
                'classes_count' => $assignments->pluck('class_id')->unique()->count(),
                'materials_count' => $materialsCount,
                'assignments_count' => $assignments->count(),
                'feedbacks_count' => $feedbacksCount,
                'graded_count' => $gradedCount,
                'total_assignments' => $totalAssignments,
                'grading_ratio' => $gradingRatio,
                'score' => min($score, 100),
            ];
        })->sortByDesc('score')->values();
    }
}
