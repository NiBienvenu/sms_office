<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentDetailStoreRequest extends FormRequest
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
            'payment_id' => ['required', 'integer', 'exists:payments,id'],
            'fee_type' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'between:-99999999.99,99999999.99'],
            'description' => ['nullable', 'string'],
        ];
    }
}
