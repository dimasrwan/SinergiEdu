<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolActionPlan extends Model
{
    use HasFactory, TenantScoped;

    /**
     * Aturan Rencana Aksi: role pembuat => daftar role target yang diizinkan.
     * Sumber aturan validasi backend (ActionPlanRequest) & filter form per role.
     */
    public const TARGET_MAP = [
        'pengawas' => ['kepala_sekolah'],
        'kepala_sekolah' => ['waka', 'guru'],
        'waka' => ['guru'],
        'guru' => ['siswa'],
    ];

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

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'in_progress' => 'Sedang Berjalan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-slate-100 text-slate-600',
            'in_progress' => 'bg-blue-100 text-blue-700',
            'completed' => 'bg-emerald-100 text-emerald-700',
            'cancelled' => 'bg-red-100 text-red-700',
            default => 'bg-slate-100 text-slate-600',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'medium' => 'bg-amber-100 text-amber-700',
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
            default => ucfirst((string) $this->category),
        };
    }

    public function getTargetRoleLabelAttribute(): ?string
    {
        if (! $this->target_role) {
            return null;
        }

        return match ($this->target_role) {
            'kepala_sekolah' => 'Kepala Sekolah',
            'waka' => 'Waka Kurikulum',
            'guru' => 'Guru',
            'siswa' => 'Siswa',
            'pengawas' => 'Pengawas',
            default => ucfirst($this->target_role),
        };
    }
}
