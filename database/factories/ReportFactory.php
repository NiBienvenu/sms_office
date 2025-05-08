<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use App\Models\Report;
use App\Models\User;

class ReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Report::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'type' => $this->faker->word(),
            'academic_year_id' => AcademicYear::factory(),
            'semester' => $this->faker->word(),
            'parameters' => '{}',
            'generated_by' => User::factory()->create()->generated_by,
            'file_path' => $this->faker->word(),
            'status' => $this->faker->word(),
            'generator_id' => User::factory(),
        ];
    }
}
