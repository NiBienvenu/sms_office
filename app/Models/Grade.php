<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'course_id' => 'integer',
        'course_enrollment_id' => 'integer',
        'academic_year_id' => 'integer',
        'grade_value' => 'decimal:2',
        'evaluation_date' => 'date',
        'recorded_by' => 'integer',
        'recorder_id' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function courseEnrollment(): BelongsTo
    {
        return $this->belongsTo(CourseEnrollment::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }


    /**
     * Get the teacher who assigned the grade.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }


    /**
     * Calculate the percentage of the score.
     */
    public function getPercentageAttribute(): float
    {
        if ($this->max_score > 0) {
            return ($this->score / $this->max_score) * 100;
        }

        return 0;
    }
}
