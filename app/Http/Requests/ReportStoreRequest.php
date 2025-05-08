<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportStoreRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'type' => ['required', 'string'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'semester' => ['nullable', 'string'],
            'parameters' => ['nullable', 'json'],
            'generated_by' => ['required'],
            'file_path' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            'generator_id' => ['required', 'integer', 'exists:Users,id'],
        ];
    }
}
