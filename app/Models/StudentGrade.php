<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'class_id',
        'subject_id',
        'academic_year_id',
        'semester_id',
        'pre_test_score',
        'assignment_score',
        'post_test_score',
        'character_score',
        'memorization_score',
        'notes',
        'supervisor_feedback',
        'supervisor_action_plan',
        'supervisor_priority',
        'supervisor_id',
        'grade_note',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Helper untuk menghitung rata-rata nilai akademik (opsional)
     */
    public function getAverageScoreAttribute(): float
    {
        $scores = array_filter([
            $this->pre_test_score,
            $this->assignment_score,
            $this->post_test_score,
            $this->character_score,
            $this->memorization_score
        ], function($value) {
            return $value !== null;
        });

        if (count($scores) === 0) {
            return 0;
        }

        return round(array_sum($scores) / count($scores), 2);
    }
}
