<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Grade;
use App\Models\RecordedBy;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\GradeController
 */
final class GradeControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $grades = Grade::factory()->count(3)->create();

        $response = $this->get(route('grades.index'));

        $response->assertOk();
        $response->assertViewIs('grade.index');
        $response->assertViewHas('grades');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('grades.create'));

        $response->assertOk();
        $response->assertViewIs('grade.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\GradeController::class,
            'store',
            \App\Http\Requests\GradeStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create();
        $course_enrollment = CourseEnrollment::factory()->create();
        $academic_year = AcademicYear::factory()->create();
        $grade_value = $this->faker->randomFloat(/** decimal_attributes **/);
        $grade_type = $this->faker->word();
        $evaluation_date = Carbon::parse($this->faker->date());
        $recorded_by = RecordedBy::factory()->create();
        $recorder = Teacher::factory()->create();

        $response = $this->post(route('grades.store'), [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'course_enrollment_id' => $course_enrollment->id,
            'academic_year_id' => $academic_year->id,
            'grade_value' => $grade_value,
            'grade_type' => $grade_type,
            'evaluation_date' => $evaluation_date->toDateString(),
            'recorded_by' => $recorded_by->id,
            'recorder_id' => $recorder->id,
        ]);

        $grades = Grade::query()
            ->where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('course_enrollment_id', $course_enrollment->id)
            ->where('academic_year_id', $academic_year->id)
            ->where('grade_value', $grade_value)
            ->where('grade_type', $grade_type)
            ->where('evaluation_date', $evaluation_date)
            ->where('recorded_by', $recorded_by->id)
            ->where('recorder_id', $recorder->id)
            ->get();
        $this->assertCount(1, $grades);
        $grade = $grades->first();

        $response->assertRedirect(route('grades.index'));
        $response->assertSessionHas('grade.id', $grade->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $grade = Grade::factory()->create();

        $response = $this->get(route('grades.show', $grade));

        $response->assertOk();
        $response->assertViewIs('grade.show');
        $response->assertViewHas('grade');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $grade = Grade::factory()->create();

        $response = $this->get(route('grades.edit', $grade));

        $response->assertOk();
        $response->assertViewIs('grade.edit');
        $response->assertViewHas('grade');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\GradeController::class,
            'update',
            \App\Http\Requests\GradeUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $grade = Grade::factory()->create();
        $student = Student::factory()->create();
        $course = Course::factory()->create();
        $course_enrollment = CourseEnrollment::factory()->create();
        $academic_year = AcademicYear::factory()->create();
        $grade_value = $this->faker->randomFloat(/** decimal_attributes **/);
        $grade_type = $this->faker->word();
        $evaluation_date = Carbon::parse($this->faker->date());
        $recorded_by = RecordedBy::factory()->create();
        $recorder = Teacher::factory()->create();

        $response = $this->put(route('grades.update', $grade), [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'course_enrollment_id' => $course_enrollment->id,
            'academic_year_id' => $academic_year->id,
            'grade_value' => $grade_value,
            'grade_type' => $grade_type,
            'evaluation_date' => $evaluation_date->toDateString(),
            'recorded_by' => $recorded_by->id,
            'recorder_id' => $recorder->id,
        ]);

        $grade->refresh();

        $response->assertRedirect(route('grades.index'));
        $response->assertSessionHas('grade.id', $grade->id);

        $this->assertEquals($student->id, $grade->student_id);
        $this->assertEquals($course->id, $grade->course_id);
        $this->assertEquals($course_enrollment->id, $grade->course_enrollment_id);
        $this->assertEquals($academic_year->id, $grade->academic_year_id);
        $this->assertEquals($grade_value, $grade->grade_value);
        $this->assertEquals($grade_type, $grade->grade_type);
        $this->assertEquals($evaluation_date, $grade->evaluation_date);
        $this->assertEquals($recorded_by->id, $grade->recorded_by);
        $this->assertEquals($recorder->id, $grade->recorder_id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $grade = Grade::factory()->create();

        $response = $this->delete(route('grades.destroy', $grade));

        $response->assertRedirect(route('grades.index'));

        $this->assertSoftDeleted($grade);
    }
}
