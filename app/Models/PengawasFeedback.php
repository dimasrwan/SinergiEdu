<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengawasFeedback extends Model
{
    use HasFactory;

    protected $table = 'pengawas_feedbacks';

    protected $fillable = [
        'pengawas_user_id',
        'student_id',
        'class_id',
        'academic_year_id',
        'semester_id',
        'content',
        'type',
    ];

    public function pengawas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengawas_user_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'positive' => 'Positif',
            'negative' => 'Negatif',
            default    => 'Netral',
        };
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'positive' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
            'negative' => 'bg-red-100 text-red-700 border border-red-200',
            default    => 'bg-slate-100 text-slate-600 border border-slate-200',
        };
    }
}
