<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentReflection extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'learning_meeting_id',
        'content',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function learningMeeting(): BelongsTo
    {
        return $this->belongsTo(LearningMeeting::class);
    }
}
