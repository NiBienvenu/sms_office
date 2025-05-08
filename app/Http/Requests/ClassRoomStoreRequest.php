<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassRoomStoreRequest extends FormRequest
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
            'code' => ['required', 'string', 'unique:class_rooms,code'],
            'level' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'capacity' => ['required', 'integer'],
            'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'schedule_id' => ['nullable', 'integer', 'exists:schedules,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'student_count' => ['required', 'integer'],
        ];
    }
}
