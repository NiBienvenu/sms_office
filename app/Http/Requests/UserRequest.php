<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
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
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . ($this->user?->id ?? '')],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'gender' => ['required'],
            'birth_date' => ['required', 'date', 'before:today'],
            'status' => ['required', 'in:active,inactive'],
            'photo' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ];

        // Ajouter la validation du mot de passe uniquement pour la création
        if ($this->isMethod('post')) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'birth_date.before' => 'The birth date must be a date before today.',
            'photo.max' => 'The photo must not be greater than 2MB.',
        ];
    }
}
