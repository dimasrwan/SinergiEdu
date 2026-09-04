<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentClass extends Pivot
{
    use \App\Traits\TenantScoped;

    // The table associated with the model.
    protected $table = 'student_classes';

    // The attributes that are mass assignable.
    protected $fillable = [
        'school_id',
        'student_id',
        'class_id',
        'academic_year_id',
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    /**
     * Relasi ke entitas Siswa.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
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


    public function school(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }
}
