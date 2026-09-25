<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomiteAspirationResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'komite_aspiration_id',
        'user_id',
        'message',
    ];

    public function aspiration(): BelongsTo
    {
        return $this->belongsTo(KomiteAspiration::class, 'komite_aspiration_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
