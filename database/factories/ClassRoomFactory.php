<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Schedule;
use App\Models\Teacher;

class ClassRoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClassRoom::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'code' => fake()->word(),
            'level' => fake()->word(),
            'description' => fake()->text(),
            'capacity' => fake()->numberBetween(-10000, 10000),
            'teacher_id' => Teacher::factory(),
            'schedule_id' => Schedule::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'student_count' => fake()->numberBetween(-10000, 10000),
        ];
    }
}
