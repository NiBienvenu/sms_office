<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->safeEmail(),
            'password' => $this->faker->password(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->text(),
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),
            'gender' => $this->faker->word(),
            'birth_date' => $this->faker->date(),
            'photo' => $this->faker->word(),
            'status' => $this->faker->word(),
            'last_login_at' => $this->faker->dateTime(),
            'remember_token' => $this->faker->uuid(),
            'email_verified_at' => $this->faker->dateTime(),
        ];
    }
}
