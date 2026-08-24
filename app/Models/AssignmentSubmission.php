<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_path',
        'notes',
        'score',
        'feedback',
    ];

    /**
     * Relasi ke Tugas terkait
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Relasi ke Siswa yang mengirim jawaban
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
