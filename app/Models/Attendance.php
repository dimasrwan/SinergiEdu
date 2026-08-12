<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'status', // Hadir, Sakit, Izin, Alpa
        'notes',
    ];

    /**
     * Relasi ke Siswa.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
