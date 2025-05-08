<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:departments,name'],
            'code' => ['required', 'string', 'unique:departments,code'],
            'description' => ['nullable', 'string'],
            'head_teacher_id' => ['nullable', 'integer', 'exists:head_teachers,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'status' => ['required', 'string'],
            'head_id' => ['required', 'integer', 'exists:Teachers,id'],
        ];
    }
}
