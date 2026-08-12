<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'phone',
        'address',
    ];

    /**
     * Relasi ke User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Mata Pelajaran (via tabel pivot teacher_subjects).
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects')->distinct();
    }

    /**
     * Relasi ke Kelas (via tabel pivot teacher_subjects).
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'teacher_subjects', 'teacher_id', 'class_id')->distinct();
    }

    /**
     * Relasi ke Kelas yang menjadi Wali Kelas.
     */
    public function homeroomClasses()
    {
        return $this->hasMany(Classroom::class, 'homeroom_teacher_id');
    }
}
