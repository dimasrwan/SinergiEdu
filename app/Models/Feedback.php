<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'teacher_id',
        'student_id',
        'subject_id',
        'title',
        'message',
        'type',
        'sender_id',
        'recipient_role',
        'recipient_id',
        'category',
        'priority',
        'status',
        'action_plan',
        'action_deadline',
    ];

    protected $casts = [
        'action_deadline' => 'date',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Label tipe feedback yang ramah pengguna.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'positive' => 'Positif',
            'negative' => 'Negatif',
            default => 'Netral',
        };
    }

    /**
     * Warna CSS untuk badge tipe feedback.
     */
    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'positive' => 'bg-emerald-100 text-emerald-800',
            'negative' => 'bg-red-100 text-red-800',
            default => 'bg-slate-100 text-slate-800',
        };
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'strategic' => 'Strategis',
            'academic' => 'Akademik',
            'operational' => 'Operasional',
            'recognition' => 'Penghargaan',
            default => ucfirst($this->category),
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return ucfirst($this->priority);
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'bg-slate-100 text-slate-600',
            'medium' => 'bg-blue-100 text-blue-700',
            'high' => 'bg-orange-100 text-orange-700',
            'urgent' => 'bg-red-100 text-red-700',
            default => 'bg-slate-100 text-slate-600',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'sent' => 'Terkirim',
            'acknowledged' => 'Dibaca',
            'actioned' => 'Ditindaklanjuti',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-slate-100 text-slate-700',
            'sent' => 'bg-blue-100 text-blue-700',
            'acknowledged' => 'bg-amber-100 text-amber-700',
            'actioned' => 'bg-emerald-100 text-emerald-700',
            default => 'bg-slate-100 text-slate-700',
        };
    }
}
