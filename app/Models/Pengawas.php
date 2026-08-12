<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengawas extends Model
{
    use HasFactory;
    
    // Explicitly define the table name because plural of 'pengawas' isn't standard in English.
    protected $table = 'pengawas';

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
}
