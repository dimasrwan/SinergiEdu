<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    use HasFactory;
    use TenantScoped;

    protected $fillable = [
        'school_id',
        'academic_year_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Tahun Ajaran.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function studentGrades(): HasMany
    {
        return $this->hasMany(StudentGrade::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Label tampilan konsisten seluruh fitur: "Semester Ganjil 2026".
     * Tahun diambil dari academic_year (mis. "2026/2027" -> Ganjil=2026, Genap=2027).
     */
    public function getLabelAttribute(): string
    {
        $years = explode('/', (string) $this->academicYear?->year);
        $isGenap = strtolower((string) $this->name) === 'genap';
        $calendarYear = $years[$isGenap ? 1 : 0] ?? null;

        return 'Semester '.$this->name.($calendarYear ? ' '.$calendarYear : '');
    }
}
