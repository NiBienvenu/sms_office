<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Department;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CourseController
 */
final class CourseControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $courses = Course::factory()->count(3)->create();

        $response = $this->get(route('courses.index'));

        $response->assertOk();
        $response->assertViewIs('course.index');
        $response->assertViewHas('courses');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('courses.create'));

        $response->assertOk();
        $response->assertViewIs('course.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CourseController::class,
            'store',
            \App\Http\Requests\CourseStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $code = $this->faker->word();
        $name = $this->faker->name();
        $subject = Subject::factory()->create();
        $department = Department::factory()->create();
        $academic_year = AcademicYear::factory()->create();
        $credits = $this->faker->numberBetween(-10000, 10000);
        $hours_per_week = $this->faker->numberBetween(-10000, 10000);
        $course_type = $this->faker->word();
        $education_level = $this->faker->word();
        $semester = $this->faker->word();
        $max_students = $this->faker->numberBetween(-10000, 10000);
        $assessment_method = $this->faker->word();
        $status = $this->faker->word();

        $response = $this->post(route('courses.store'), [
            'code' => $code,
            'name' => $name,
            'subject_id' => $subject->id,
            'department_id' => $department->id,
            'academic_year_id' => $academic_year->id,
            'credits' => $credits,
            'hours_per_week' => $hours_per_week,
            'course_type' => $course_type,
            'education_level' => $education_level,
            'semester' => $semester,
            'max_students' => $max_students,
            'assessment_method' => $assessment_method,
            'status' => $status,
        ]);

        $courses = Course::query()
            ->where('code', $code)
            ->where('name', $name)
            ->where('subject_id', $subject->id)
            ->where('department_id', $department->id)
            ->where('academic_year_id', $academic_year->id)
            ->where('credits', $credits)
            ->where('hours_per_week', $hours_per_week)
            ->where('course_type', $course_type)
            ->where('education_level', $education_level)
            ->where('semester', $semester)
            ->where('max_students', $max_students)
            ->where('assessment_method', $assessment_method)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $courses);
        $course = $courses->first();

        $response->assertRedirect(route('courses.index'));
        $response->assertSessionHas('course.id', $course->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $course = Course::factory()->create();

        $response = $this->get(route('courses.show', $course));

        $response->assertOk();
        $response->assertViewIs('course.show');
        $response->assertViewHas('course');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $course = Course::factory()->create();

        $response = $this->get(route('courses.edit', $course));

        $response->assertOk();
        $response->assertViewIs('course.edit');
        $response->assertViewHas('course');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CourseController::class,
            'update',
            \App\Http\Requests\CourseUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $course = Course::factory()->create();
        $code = $this->faker->word();
        $name = $this->faker->name();
        $subject = Subject::factory()->create();
        $department = Department::factory()->create();
        $academic_year = AcademicYear::factory()->create();
        $credits = $this->faker->numberBetween(-10000, 10000);
        $hours_per_week = $this->faker->numberBetween(-10000, 10000);
        $course_type = $this->faker->word();
        $education_level = $this->faker->word();
        $semester = $this->faker->word();
        $max_students = $this->faker->numberBetween(-10000, 10000);
        $assessment_method = $this->faker->word();
        $status = $this->faker->word();

        $response = $this->put(route('courses.update', $course), [
            'code' => $code,
            'name' => $name,
            'subject_id' => $subject->id,
            'department_id' => $department->id,
            'academic_year_id' => $academic_year->id,
            'credits' => $credits,
            'hours_per_week' => $hours_per_week,
            'course_type' => $course_type,
            'education_level' => $education_level,
            'semester' => $semester,
            'max_students' => $max_students,
            'assessment_method' => $assessment_method,
            'status' => $status,
        ]);

        $course->refresh();

        $response->assertRedirect(route('courses.index'));
        $response->assertSessionHas('course.id', $course->id);

        $this->assertEquals($code, $course->code);
        $this->assertEquals($name, $course->name);
        $this->assertEquals($subject->id, $course->subject_id);
        $this->assertEquals($department->id, $course->department_id);
        $this->assertEquals($academic_year->id, $course->academic_year_id);
        $this->assertEquals($credits, $course->credits);
        $this->assertEquals($hours_per_week, $course->hours_per_week);
        $this->assertEquals($course_type, $course->course_type);
        $this->assertEquals($education_level, $course->education_level);
        $this->assertEquals($semester, $course->semester);
        $this->assertEquals($max_students, $course->max_students);
        $this->assertEquals($assessment_method, $course->assessment_method);
        $this->assertEquals($status, $course->status);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $course = Course::factory()->create();

        $response = $this->delete(route('courses.destroy', $course));

        $response->assertRedirect(route('courses.index'));

        $this->assertSoftDeleted($course);
    }
}
