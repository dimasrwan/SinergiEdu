<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengawas extends Model
{
    use \App\Traits\TenantScoped;

    use HasFactory;
    
    // Explicitly define the table name because plural of 'pengawas' isn't standard in English.
    protected $table = 'pengawas';

    protected $fillable = [
        'school_id',
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


    public function school(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }
}
