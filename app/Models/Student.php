<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'matricule',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'gender',
        'birth_date',
        'birth_place',
        'nationality',
        'photo',
        'admission_date',
        'current_class',
        'academic_year_id',
        'education_level',
        'previous_school',
        'guardian_name',
        'guardian_relationship',
        'guardian_phone',
        'guardian_email',
        'guardian_address',
        'guardian_occupation',
        'health_issues',
        'blood_group',
        'emergency_contact',
        'status',
        'additional_info',
        'class_room_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'birth_date' => 'date',
        'admission_date' => 'date',
        'academic_year_id' => 'integer',
        'additional_info' => 'array',
    ];

    public function classRoom(){
        return $this->belongsTo(ClassRoom::class);
    }
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function courseEnrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }


}
