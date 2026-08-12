<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    /**
     * Hubungkan ke tabel classes (karena class adalah keyword cadangan di PHP).
     */
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'grade_level',
        'academic_year_id',
        'homeroom_teacher_id',
    ];

    /**
     * Relasi ke Tahun Ajaran.
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Relasi ke Wali Kelas (Teacher).
     */
    public function homeroomTeacher()
    {
        return $this->belongsTo(Teacher::class, 'homeroom_teacher_id');
    }

    /**
     * Relasi ke Siswa.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_classes', 'class_id', 'student_id')
                    ->withPivot('academic_year_id')
                    ->withTimestamps();
    }
}
