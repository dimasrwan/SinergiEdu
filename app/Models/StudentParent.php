<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentParent extends Model
{
    use \App\Traits\TenantScoped;

    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'parents';

    protected $fillable = [
        'school_id',
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


    public function school(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }
}
