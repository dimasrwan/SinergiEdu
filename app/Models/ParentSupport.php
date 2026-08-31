<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentSupport extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'school_id',
        'student_id',
        'academic_year_id',
        'semester_id',
        'week_number',
        'support_description',
        'general_feedback',
        'action_plan',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
