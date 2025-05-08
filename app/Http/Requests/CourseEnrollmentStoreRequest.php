<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseEnrollmentStoreRequest extends FormRequest
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
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'semester' => ['required', 'string'],
            'status' => ['required', 'string'],
        ];
    }
}
