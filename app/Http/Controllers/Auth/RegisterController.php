<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'pin_code' => ['nullable', 'string', 'min:4', 'max:4', function ($attribute, $value, $fail) use ($data) {
                if (isset($data['activate_pin']) && $data['activate_pin'] && !$value) {
                    $fail('Le code PIN est requis lorsque l\'activation du PIN est activée.');
                }
            }],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'in:male,female,other'],
            'birth_date' => ['required', 'date', 'before:today'],
            'photo' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ]);
    }

    protected function create(array $data)
    {
        $photoPath = null;

        if (isset($data['photo']) && $data['photo']->isValid()) {
            $photoPath = $data['photo']->store('users/photos', 'public');
        }

        $pinCode = isset($data['pin_code']) ? $data['pin_code'] : null;

        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'pin_code' => $pinCode,
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city' => $data['city'],
            'country' => $data['country'],
            'gender' => $data['gender'],
            'birth_date' => $data['birth_date'],
            'photo' => $photoPath,
            'status' => 'active',
            'last_login_at' => now(),
        ]);
    }

}
