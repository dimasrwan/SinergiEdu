<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
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

        return $this->classes()->wherePivot('academic_year_id', $activeYear->id)->first();
    }
}
