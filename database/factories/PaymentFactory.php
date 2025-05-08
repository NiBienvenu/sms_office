<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use App\Models\Payment;
use App\Models\Student;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'amount' => $this->faker->randomFloat(2, 0, 99999999.99),
            'payment_type' => $this->faker->word(),
            'payment_date' => $this->faker->date(),
            'status' => $this->faker->word(),
            'reference_number' => $this->faker->word(),
            'semester' => $this->faker->word(),
        ];
    }
}
