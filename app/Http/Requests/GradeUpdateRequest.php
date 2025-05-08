<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeUpdateRequest extends FormRequest
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
            'course_enrollment_id' => ['required', 'integer', 'exists:course_enrollments,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'grade_value' => ['required', 'numeric', 'between:-999.99,999.99'],
            'grade_type' => ['required', 'string'],
            'evaluation_date' => ['required', 'date'],
            'recorded_by' => ['required'],
            'recorder_id' => ['required', 'integer', 'exists:Teachers,id'],
        ];
    }
}
