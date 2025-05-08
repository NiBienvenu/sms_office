<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseUpdateRequest extends FormRequest
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
            'code' => ['required', 'string', 'unique:courses,code'],
            'name' => ['required', 'string'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'description' => ['nullable', 'string'],
            'credits' => ['required', 'integer'],
            'hours_per_week' => ['required', 'integer'],
            'course_type' => ['required', 'string'],
            'education_level' => ['required', 'string'],
            'semester' => ['required', 'string'],
            'max_students' => ['required', 'integer'],
            'prerequisites' => ['nullable', 'json'],
            'syllabus' => ['nullable', 'string'],
            'objectives' => ['nullable', 'string'],
            'assessment_method' => ['required', 'string'],
            'status' => ['required', 'string'],
        ];
    }
}
