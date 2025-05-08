<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Department;
use App\Models\Teacher;

class TeacherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'employee_id' => $this->faker->word(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->text(),
            'gender' => $this->faker->word(),
            'birth_date' => $this->faker->date(),
            'nationality' => $this->faker->word(),
            'photo' => $this->faker->word(),
            'joining_date' => $this->faker->date(),
            'contract_type' => $this->faker->word(),
            'employment_status' => $this->faker->word(),
            'qualification' => $this->faker->word(),
            'specialization' => $this->faker->word(),
            'experience_years' => $this->faker->numberBetween(-10000, 10000),
            'previous_employment' => $this->faker->text(),
            'department_id' => Department::factory(),
            'position' => $this->faker->word(),
            'salary_grade' => $this->faker->word(),
            'bank_account' => $this->faker->word(),
            'tax_number' => $this->faker->word(),
            'social_security_number' => $this->faker->word(),
            'emergency_contact_name' => $this->faker->word(),
            'emergency_contact_phone' => $this->faker->word(),
            'additional_info' => '{}',
        ];
    }
}
