<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Subject;

class SubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'code' => $this->faker->word(),
            'department_id' => Department::factory(),
            'description' => $this->faker->text(),
            'academic_year_id' => AcademicYear::factory(),
            'status' => $this->faker->word(),
        ];
    }
}
