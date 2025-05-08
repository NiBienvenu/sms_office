<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
{
    $studentId = $this->route('student');
    return [
        'matricule' => ['required', 'string', Rule::unique('students', 'matricule')->ignore($studentId)],
        'first_name' => ['required', 'string'],
        'last_name' => ['required', 'string'],
        'email' => ['nullable', 'email', Rule::unique('students', 'email')->ignore($studentId)],
        'phone' => ['nullable', 'string'],
        'address' => ['required', 'string'],
        'gender' => ['required', 'string'],
        'birth_date' => ['required', 'date'],
        'birth_place' => ['required', 'string'],
        'nationality' => ['required', 'string'],
        'photo' => ['nullable', 'file'],
        'admission_date' => ['required', 'date'],
        'current_class' => ['nullable', 'string'],
        'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
        'education_level' => ['required', 'string'],
        'previous_school' => ['nullable', 'string'],
        'guardian_name' => ['required', 'string'],
        'guardian_relationship' => ['required', 'string'],
        'guardian_phone' => ['required', 'string'],
        'guardian_email' => ['nullable', 'string'],
        'guardian_address' => ['required', 'string'],
        'guardian_occupation' => ['required', 'string'],
        'health_issues' => ['nullable', 'string'],
        'blood_group' => ['nullable', 'string'],
        'emergency_contact' => ['required', 'string'],
        'status' => ['required', 'string'],
        'additional_info' => ['nullable', 'json'],
        'class_room_id' => ['required', 'integer', 'exists:class_rooms,id'],
    ];
}
}
