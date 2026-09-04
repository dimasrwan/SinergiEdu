<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use \App\Traits\TenantScoped;

    use HasFactory;

    protected $fillable = [
        'school_id',
        'school_name',
        'school_npsn',
        'school_address',
        'school_phone',
        'school_email',
        'school_logo',
    ];


    public function school(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }
}
