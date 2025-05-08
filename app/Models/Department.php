<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'head_teacher_id',
        'academic_year_id',
        'status',
        'head_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'head_teacher_id' => 'integer',
        'academic_year_id' => 'integer',
        'head_id' => 'integer',
    ];

    public function head(): BelongsTo
    {
        return $this->belongsTo(Teacher::class,'head_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function headTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class,'head_teacher_id');
    }

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
