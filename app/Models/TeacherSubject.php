<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherSubject extends Pivot
{
    use \App\Traits\TenantScoped;

    // The table associated with the model.
    protected $table = 'teacher_subjects';

    // The attributes that are mass assignable.
    protected $fillable = [
        'school_id',
        'teacher_id',
        'subject_id',
        'class_id',
        'academic_year_id',
        'semester_id',
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    /**
     * Relasi ke entitas Guru.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Relasi ke entitas Mata Pelajaran.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relasi ke entitas Kelas.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    /**
     * Relasi ke entitas Tahun Ajaran.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Relasi ke entitas Semester.
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }


    public function school(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }
}
