<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectUpdateRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'code' => ['required', 'string', 'unique:subjects,code'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'description' => ['nullable', 'string'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'status' => ['required', 'string'],
        ];
    }
}
