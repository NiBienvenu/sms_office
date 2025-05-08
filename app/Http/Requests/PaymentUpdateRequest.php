<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentUpdateRequest extends FormRequest
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
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'amount' => ['required', 'numeric', 'between:-99999999.99,99999999.99'],
            'payment_type' => ['required', 'string'],
            'payment_date' => ['required', 'date'],
            'status' => ['required', 'string'],
            'reference_number' => ['required', 'string', 'unique:payments,reference_number'],
            'semester' => ['required', 'string'],
        ];
    }
}
