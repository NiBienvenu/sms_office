<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherUpdateRequest extends FormRequest
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
        return [
            'employee_id' => ['required', 'string', 'unique:teachers,employee_id'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:teachers,email'],
            'phone' => ['required', 'string'],
            'address' => ['required', 'string'],
            'gender' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'nationality' => ['required', 'string'],
            'photo' => ['nullable', 'string'],
            'joining_date' => ['required', 'date'],
            'contract_type' => ['required', 'string'],
            'employment_status' => ['required', 'string'],
            'qualification' => ['required', 'string'],
            'specialization' => ['required', 'string'],
            'experience_years' => ['required', 'integer'],
            'previous_employment' => ['nullable', 'string'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'position' => ['required', 'string'],
            'salary_grade' => ['required', 'string'],
            'bank_account' => ['nullable', 'string'],
            'tax_number' => ['nullable', 'string'],
            'social_security_number' => ['nullable', 'string'],
            'emergency_contact_name' => ['required', 'string'],
            'emergency_contact_phone' => ['required', 'string'],
            'additional_info' => ['nullable', 'json'],
        ];
    }
}
