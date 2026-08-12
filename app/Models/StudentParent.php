<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentParent extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'parents';

    protected $fillable = [
        'user_id',
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
     * Relasi ke Anak/Siswa.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
}
