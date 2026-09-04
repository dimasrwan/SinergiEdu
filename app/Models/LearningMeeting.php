<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LearningMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 'class_id', 'subject_id', 'academic_year_id', 'semester_id',
        'meeting_number', 'meeting_date', 'topic', 'tools_materials', 'notes',
        'material_file_path', 'assignment_file_path',
    ];

    protected $casts = [
        'meeting_date' => 'date',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(StudentAssessment::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }
}
