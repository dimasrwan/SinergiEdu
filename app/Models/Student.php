<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use \App\Traits\TenantScoped;

    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'parent_id',
        'nisn',
        'nis',
        'gender',
        'date_of_birth',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Relasi ke User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Orang Tua.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(StudentParent::class, 'parent_id');
    }

    /**
     * Relasi ke Nilai Siswa.
     */
    public function grades(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StudentGrade::class);
    }

    public function assessments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StudentAssessment::class);
    }

    /**
     * Alias untuk grades() - digunakan di controller feedback.
     */
    public function studentGrades(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->grades();
    }

    /**
     * Relasi ke Riwayat Kelas (via tabel pivot student_classes).
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'student_classes', 'student_id', 'class_id')
                    ->withPivot('academic_year_id')
                    ->withTimestamps();
    }

    /**
     * Mendapatkan Kelas aktif siswa pada tahun ajaran yang sedang aktif saat ini.
     */
    public function activeClassroom()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return null;
        }

        return $this->classes()->where('student_classes.academic_year_id', $activeYear->id)->first();
    }


    public function school(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }
    
    public function submissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\AssignmentSubmission::class);
    }
}
