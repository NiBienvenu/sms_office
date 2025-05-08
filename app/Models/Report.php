<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'type',
        'academic_year_id',
        'semester',
        'parameters',
        'generated_by',
        'file_path',
        'status',
        'generator_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'academic_year_id' => 'integer',
        'parameters' => 'array',
        'generated_by' => 'integer',
        'generator_id' => 'integer',
    ];

    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
