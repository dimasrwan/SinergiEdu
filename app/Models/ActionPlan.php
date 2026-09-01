<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengawas_user_id',
        'class_id',
        'academic_year_id',
        'semester_id',
        'title',
        'content',
        'priority',
        'status',
    ];

    public function pengawas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengawas_user_id');
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

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'high'   => 'Tinggi',
            'low'    => 'Rendah',
            default  => 'Sedang',
        };
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'high'   => 'bg-red-100 text-red-700 border border-red-200',
            'low'    => 'bg-slate-100 text-slate-600 border border-slate-200',
            default  => 'bg-amber-100 text-amber-700 border border-amber-200',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'published' ? 'Diterbitkan' : 'Draft';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status === 'published'
            ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
            : 'bg-slate-100 text-slate-500 border border-slate-200';
    }
}
