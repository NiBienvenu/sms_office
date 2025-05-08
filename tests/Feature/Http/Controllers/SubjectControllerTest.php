<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SubjectController
 */
final class SubjectControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $subjects = Subject::factory()->count(3)->create();

        $response = $this->get(route('subjects.index'));

        $response->assertOk();
        $response->assertViewIs('subject.index');
        $response->assertViewHas('subjects');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('subjects.create'));

        $response->assertOk();
        $response->assertViewIs('subject.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SubjectController::class,
            'store',
            \App\Http\Requests\SubjectStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name();
        $code = $this->faker->word();
        $department = Department::factory()->create();
        $academic_year = AcademicYear::factory()->create();
        $status = $this->faker->word();

        $response = $this->post(route('subjects.store'), [
            'name' => $name,
            'code' => $code,
            'department_id' => $department->id,
            'academic_year_id' => $academic_year->id,
            'status' => $status,
        ]);

        $subjects = Subject::query()
            ->where('name', $name)
            ->where('code', $code)
            ->where('department_id', $department->id)
            ->where('academic_year_id', $academic_year->id)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $subjects);
        $subject = $subjects->first();

        $response->assertRedirect(route('subjects.index'));
        $response->assertSessionHas('subject.id', $subject->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $subject = Subject::factory()->create();

        $response = $this->get(route('subjects.show', $subject));

        $response->assertOk();
        $response->assertViewIs('subject.show');
        $response->assertViewHas('subject');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $subject = Subject::factory()->create();

        $response = $this->get(route('subjects.edit', $subject));

        $response->assertOk();
        $response->assertViewIs('subject.edit');
        $response->assertViewHas('subject');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SubjectController::class,
            'update',
            \App\Http\Requests\SubjectUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $subject = Subject::factory()->create();
        $name = $this->faker->name();
        $code = $this->faker->word();
        $department = Department::factory()->create();
        $academic_year = AcademicYear::factory()->create();
        $status = $this->faker->word();

        $response = $this->put(route('subjects.update', $subject), [
            'name' => $name,
            'code' => $code,
            'department_id' => $department->id,
            'academic_year_id' => $academic_year->id,
            'status' => $status,
        ]);

        $subject->refresh();

        $response->assertRedirect(route('subjects.index'));
        $response->assertSessionHas('subject.id', $subject->id);

        $this->assertEquals($name, $subject->name);
        $this->assertEquals($code, $subject->code);
        $this->assertEquals($department->id, $subject->department_id);
        $this->assertEquals($academic_year->id, $subject->academic_year_id);
        $this->assertEquals($status, $subject->status);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $subject = Subject::factory()->create();

        $response = $this->delete(route('subjects.destroy', $subject));

        $response->assertRedirect(route('subjects.index'));

        $this->assertSoftDeleted($subject);
    }
}
