<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollaborativeAction extends Model
{
    protected $fillable = [
        'assessment_id',
        'role_type',
        'user_id',
        'feedback_content',
        'action_plan',
        'week_number',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(StudentAssessment::class, 'assessment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
