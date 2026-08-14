<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use \App\Traits\TenantScoped;

    use HasFactory;

    protected $fillable = [
        'school_id',
        'year',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Semester.
     */
    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }


    public function school(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }
}
