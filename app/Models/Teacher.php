<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'gender',
        'birth_date',
        'nationality',
        'photo',
        'joining_date',
        'contract_type',
        'employment_status',
        'qualification',
        'specialization',
        'experience_years',
        'previous_employment',
        'department_id',
        'position',
        'salary_grade',
        'bank_account',
        'tax_number',
        'social_security_number',
        'emergency_contact_name',
        'emergency_contact_phone',
        'additional_info',
    ];

    protected $append = ['fullname'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'birth_date' => 'date',
        'joining_date' => 'date',
        'department_id' => 'integer',
        'additional_info' => 'array',
    ];

    public function getFullnameAttribute(){
        return $this->first_name.' '.$this->last_name;
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }
}
