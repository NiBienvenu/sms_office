<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Department;
use App\Models\Subject;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->word(),
            'name' => $this->faker->name(),
            'subject_id' => Subject::factory(),
            'department_id' => Department::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'description' => $this->faker->text(),
            'credits' => $this->faker->numberBetween(-10000, 10000),
            'hours_per_week' => $this->faker->numberBetween(-10000, 10000),
            'course_type' => $this->faker->word(),
            'education_level' => $this->faker->word(),
            'semester' => $this->faker->word(),
            'max_students' => $this->faker->numberBetween(-10000, 10000),
            'prerequisites' => '{}',
            'syllabus' => $this->faker->text(),
            'objectives' => $this->faker->text(),
            'assessment_method' => $this->faker->word(),
            'status' => $this->faker->word(),
        ];
    }
}
