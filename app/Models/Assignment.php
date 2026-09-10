<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'class_id',
        'subject_id',
        'learning_meeting_id',
        'material_id',
        'title',
        'description',
        'deadline',
        'attachment_path',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    /**
     * Relasi ke Guru yang memberikan tugas
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Relasi ke Kelas sasaran tugas
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    /**
     * Relasi ke Mata Pelajaran
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relasi ke Pertemuan Pembelajaran (Opsional)
     */
    public function learningMeeting(): BelongsTo
    {
        return $this->belongsTo(LearningMeeting::class);
    }

    /**
     * Relasi ke Materi Pembelajaran Terkait (Opsional)
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Relasi ke kumpulan jawaban yang disubmit siswa
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
