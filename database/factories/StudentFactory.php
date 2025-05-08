<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use App\Models\Student;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'matricule' => $this->faker->word(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->text(),
            'gender' => $this->faker->word(),
            'birth_date' => $this->faker->date(),
            'birth_place' => $this->faker->word(),
            'nationality' => $this->faker->word(),
            'photo' => $this->faker->word(),
            'admission_date' => $this->faker->date(),
            'current_class' => $this->faker->word(),
            'academic_year_id' => AcademicYear::factory(),
            'education_level' => $this->faker->word(),
            'previous_school' => $this->faker->word(),
            'guardian_name' => $this->faker->word(),
            'guardian_relationship' => $this->faker->word(),
            'guardian_phone' => $this->faker->word(),
            'guardian_email' => $this->faker->word(),
            'guardian_address' => $this->faker->text(),
            'guardian_occupation' => $this->faker->word(),
            'health_issues' => $this->faker->text(),
            'blood_group' => $this->faker->word(),
            'emergency_contact' => $this->faker->word(),
            'status' => $this->faker->word(),
            'additional_info' => '{}',
        ];
    }
}
