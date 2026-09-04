<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'learning_meeting_id', 'student_id', 'pre_test_score', 'assignment_score',
        'post_test_score', 'character_score', 'memorization_score', 'memorization_juz',
        'memorization_ayat', 'notes',
    ];

    public function learningMeeting(): BelongsTo
    {
        return $this->belongsTo(LearningMeeting::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function getAverageScoreAttribute(): float
    {
        $scores = array_filter([
            $this->pre_test_score, $this->assignment_score, $this->post_test_score,
            $this->character_score, $this->memorization_score,
        ], static fn ($score) => $score !== null);

        return $scores === [] ? 0 : round(array_sum($scores) / count($scores), 2);
    }
}
