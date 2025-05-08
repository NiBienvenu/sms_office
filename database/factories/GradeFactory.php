<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;

class GradeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Grade::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'course_id' => Course::factory(),
            'course_enrollment_id' => CourseEnrollment::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'grade_value' => $this->faker->randomFloat(2, 0, 999.99),
            'grade_type' => $this->faker->word(),
            'evaluation_date' => $this->faker->date(),
            'recorded_by' => Teacher::factory()->create()->recorded_by,
            'recorder_id' => Teacher::factory(),
        ];
    }
}
