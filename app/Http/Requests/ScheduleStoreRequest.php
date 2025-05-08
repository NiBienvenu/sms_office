<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleStoreRequest extends FormRequest
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
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'day_of_week' => ['required', 'string'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'room' => ['required', 'string'],
        ];
    }
}
