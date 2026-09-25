<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceReport extends Model
{
    use HasFactory;
    use TenantScoped;

    protected $fillable = [
        'school_id',
        'pengawas_user_id',
        'teacher_id',
        'class_id',
        'title',
        'content',
        'recommendations',
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
}
