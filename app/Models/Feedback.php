<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'teacher_id',
        'student_id',
        'subject_id',
        'title',
        'message',
        'type',
    ];

    /**
     * Label tipe feedback yang ramah pengguna.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'positive' => 'Positif',
            'negative' => 'Negatif',
            default => 'Netral',
        };
    }

    /**
     * Warna CSS untuk badge tipe feedback.
     */
    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'positive' => 'bg-emerald-100 text-emerald-800',
            'negative' => 'bg-red-100 text-red-800',
            default => 'bg-slate-100 text-slate-800',
        };
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
