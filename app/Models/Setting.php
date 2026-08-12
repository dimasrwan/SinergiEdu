<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'school_npsn',
        'school_address',
        'school_phone',
        'school_email',
        'school_logo',
    ];
}
