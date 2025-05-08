<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Student;

class CourseEnrollmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CourseEnrollment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'course_id' => Course::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'semester' => $this->faker->word(),
            'status' => $this->faker->word(),
        ];
    }
}
