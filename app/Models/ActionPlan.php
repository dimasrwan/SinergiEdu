<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionPlan extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'school_id',
        'user_id',
        'title',
        'description',
        'target_role',
        'target_user_id',
        'category',
        'priority',
        'status',
        'start_date',
        'due_date',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'in_progress' => 'Dikerjakan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status ? ucfirst($this->status) : '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-slate-100 text-slate-700',
            'in_progress' => 'bg-blue-100 text-blue-700',
            'completed' => 'bg-emerald-100 text-emerald-700',
            'cancelled' => 'bg-red-100 text-red-700',
            default => 'bg-slate-100 text-slate-700',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'urgent' => 'Mendesak',
            default => $this->priority ? ucfirst($this->priority) : '-',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'bg-slate-100 text-slate-600',
            'medium' => 'bg-blue-100 text-blue-700',
            'high' => 'bg-orange-100 text-orange-700',
            'urgent' => 'bg-red-100 text-red-700',
            default => 'bg-slate-100 text-slate-600',
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'academic' => 'Akademik',
            'character' => 'Karakter',
            'memorization' => 'Hafalan',
            'operational' => 'Operasional',
            default => $this->category ? ucfirst($this->category) : '-',
        };
    }

    public function getTargetRoleLabelAttribute(): string
    {
        return match ($this->target_role) {
            'guru' => 'Guru',
            'waka' => 'Waka Kurikulum',
            'pengawas' => 'Pengawas',
            default => 'Umum',
        };
    }
}
