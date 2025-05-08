<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'password'],
            'phone' => ['required', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'country' => ['required', 'string'],
            'gender' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'photo' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            'last_login_at' => ['nullable'],
            'remember_token' => ['nullable', 'string'],
            'email_verified_at' => ['nullable'],
        ];
    }
}
