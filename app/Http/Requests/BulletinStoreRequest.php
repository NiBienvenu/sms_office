<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulletinStoreRequest extends FormRequest
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
            'student_id' => ['nullable', 'integer', 'exists:Students,id'],
            'class_room_id' => ['nullable', 'integer', 'exists:,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:,id'],
            'trimester' => ['nullable', 'integer'],
            'generated_at' => ['nullable'],
            'status' => ['required', 'string'],
            'average' => ['nullable', 'string'],
            'rank' => ['nullable', 'integer'],
            'teacher_comments' => ['nullable', 'string'],
            'principal_comments' => ['nullable', 'string'],
            'pdf_path' => ['nullable', 'string'],
            'unique' => ['required', 'string'],
        ];
    }
}
