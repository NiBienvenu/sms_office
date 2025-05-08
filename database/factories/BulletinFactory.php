<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Bulletin;
use App\Models\Student;

class BulletinFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bulletin::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'class_room_id' => ClassRoom::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'trimester' => fake()->numberBetween(0, 8),
            'generated_at' => fake()->dateTime(),
            'status' => fake()->word(),
            'average' => fake()->word(),
            'rank' => fake()->numberBetween(0, 10000),
            'teacher_comments' => fake()->text(),
            'principal_comments' => fake()->text(),
            'pdf_path' => fake()->word(),
            'unique' => fake()->word(),
        ];
    }
}
