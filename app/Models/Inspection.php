<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inspection extends Model
{
    use HasFactory;
    use TenantScoped;

    protected $fillable = [
        'school_id',
        'pengawas_user_id',
        'teacher_id',
        'class_id',
        'title',
        'description',
        'inspection_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'inspection_date' => 'datetime',
    ];

    public function pengawas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengawas_user_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'in_progress' => 'Sedang Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Terjadwal',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'in_progress' => 'bg-amber-100 text-amber-700 border border-amber-200',
            'completed' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
            'cancelled' => 'bg-red-100 text-red-700 border border-red-200',
            default => 'bg-blue-100 text-blue-700 border border-blue-200',
        };
    }
}
