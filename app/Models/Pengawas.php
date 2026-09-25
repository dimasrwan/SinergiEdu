<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pengawas extends Model
{
    use HasFactory;

    protected $table = 'pengawas';

    protected $fillable = [
        'user_id',
        'nip',
        'phone',
        'address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi many-to-many ke sekolah via pivot pengawas_school.
     */
    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'pengawas_school', 'pengawas_user_id', 'school_id', 'user_id');
    }
}
