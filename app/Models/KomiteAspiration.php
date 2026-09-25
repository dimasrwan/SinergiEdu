<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\KomiteAspirationResponse;

class KomiteAspiration extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'school_id',
        'user_id',
        'title',
        'content',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(KomiteAspirationResponse::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Tanggapan',
            'reviewed' => 'Dalam Peninjauan',
            'acted_upon' => 'Telah Ditindaklanjuti',
            'closed' => 'Selesai / Ditutup',
            default => 'Menunggu',
        };
    }
}
