<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inspection extends Model
{
    protected $fillable = [
        'title',
        'content',
        'inspection_date',
        'location',
        'status',
        'school_id',
        'created_by',
        'is_archived',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'is_archived' => 'boolean',
    ];

    /**
     * Scope query untuk hanya menampilkan data yang diarsipkan.
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    /**
     * Scope query untuk hanya menampilkan data yang belum diarsipkan.
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}