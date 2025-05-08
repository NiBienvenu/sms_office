<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Teacher;

class DepartmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Department::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'code' => $this->faker->word(),
            'description' => $this->faker->text(),
            'head_teacher_id' => Teacher::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'status' => $this->faker->word(),
            'head_id' => Teacher::factory(),
        ];
    }
}
