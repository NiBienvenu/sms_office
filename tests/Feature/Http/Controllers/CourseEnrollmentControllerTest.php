<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CourseEnrollmentController
 */
final class CourseEnrollmentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $courseEnrollments = CourseEnrollment::factory()->count(3)->create();

        $response = $this->get(route('course-enrollments.index'));

        $response->assertOk();
        $response->assertViewIs('courseEnrollment.index');
        $response->assertViewHas('courseEnrollments');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('course-enrollments.create'));

        $response->assertOk();
        $response->assertViewIs('courseEnrollment.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CourseEnrollmentController::class,
            'store',
            \App\Http\Requests\CourseEnrollmentStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create();
        $academic_year = AcademicYear::factory()->create();
        $semester = $this->faker->word();
        $status = $this->faker->word();

        $response = $this->post(route('course-enrollments.store'), [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'academic_year_id' => $academic_year->id,
            'semester' => $semester,
            'status' => $status,
        ]);

        $courseEnrollments = CourseEnrollment::query()
            ->where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('academic_year_id', $academic_year->id)
            ->where('semester', $semester)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $courseEnrollments);
        $courseEnrollment = $courseEnrollments->first();

        $response->assertRedirect(route('courseEnrollments.index'));
        $response->assertSessionHas('courseEnrollment.id', $courseEnrollment->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $courseEnrollment = CourseEnrollment::factory()->create();

        $response = $this->get(route('course-enrollments.show', $courseEnrollment));

        $response->assertOk();
        $response->assertViewIs('courseEnrollment.show');
        $response->assertViewHas('courseEnrollment');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $courseEnrollment = CourseEnrollment::factory()->create();

        $response = $this->get(route('course-enrollments.edit', $courseEnrollment));

        $response->assertOk();
        $response->assertViewIs('courseEnrollment.edit');
        $response->assertViewHas('courseEnrollment');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CourseEnrollmentController::class,
            'update',
            \App\Http\Requests\CourseEnrollmentUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $courseEnrollment = CourseEnrollment::factory()->create();
        $student = Student::factory()->create();
        $course = Course::factory()->create();
        $academic_year = AcademicYear::factory()->create();
        $semester = $this->faker->word();
        $status = $this->faker->word();

        $response = $this->put(route('course-enrollments.update', $courseEnrollment), [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'academic_year_id' => $academic_year->id,
            'semester' => $semester,
            'status' => $status,
        ]);

        $courseEnrollment->refresh();

        $response->assertRedirect(route('courseEnrollments.index'));
        $response->assertSessionHas('courseEnrollment.id', $courseEnrollment->id);

        $this->assertEquals($student->id, $courseEnrollment->student_id);
        $this->assertEquals($course->id, $courseEnrollment->course_id);
        $this->assertEquals($academic_year->id, $courseEnrollment->academic_year_id);
        $this->assertEquals($semester, $courseEnrollment->semester);
        $this->assertEquals($status, $courseEnrollment->status);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $courseEnrollment = CourseEnrollment::factory()->create();

        $response = $this->delete(route('course-enrollments.destroy', $courseEnrollment));

        $response->assertRedirect(route('courseEnrollments.index'));

        $this->assertSoftDeleted($courseEnrollment);
    }
}
