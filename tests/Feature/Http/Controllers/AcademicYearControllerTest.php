<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AcademicYearController
 */
final class AcademicYearControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $academicYears = AcademicYear::factory()->count(3)->create();

        $response = $this->get(route('academic-years.index'));

        $response->assertOk();
        $response->assertViewIs('academicYear.index');
        $response->assertViewHas('academicYears');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('academic-years.create'));

        $response->assertOk();
        $response->assertViewIs('academicYear.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AcademicYearController::class,
            'store',
            \App\Http\Requests\AcademicYearStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $year = $this->faker->word();
        $start_date = Carbon::parse($this->faker->date());
        $end_date = Carbon::parse($this->faker->date());
        $status = $this->faker->word();
        $current = $this->faker->boolean();

        $response = $this->post(route('academic-years.store'), [
            'year' => $year,
            'start_date' => $start_date->toDateString(),
            'end_date' => $end_date->toDateString(),
            'status' => $status,
            'current' => $current,
        ]);

        $academicYears = AcademicYear::query()
            ->where('year', $year)
            ->where('start_date', $start_date)
            ->where('end_date', $end_date)
            ->where('status', $status)
            ->where('current', $current)
            ->get();
        $this->assertCount(1, $academicYears);
        $academicYear = $academicYears->first();

        $response->assertRedirect(route('academicYears.index'));
        $response->assertSessionHas('academicYear.id', $academicYear->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $academicYear = AcademicYear::factory()->create();

        $response = $this->get(route('academic-years.show', $academicYear));

        $response->assertOk();
        $response->assertViewIs('academicYear.show');
        $response->assertViewHas('academicYear');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $academicYear = AcademicYear::factory()->create();

        $response = $this->get(route('academic-years.edit', $academicYear));

        $response->assertOk();
        $response->assertViewIs('academicYear.edit');
        $response->assertViewHas('academicYear');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AcademicYearController::class,
            'update',
            \App\Http\Requests\AcademicYearUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $academicYear = AcademicYear::factory()->create();
        $year = $this->faker->word();
        $start_date = Carbon::parse($this->faker->date());
        $end_date = Carbon::parse($this->faker->date());
        $status = $this->faker->word();
        $current = $this->faker->boolean();

        $response = $this->put(route('academic-years.update', $academicYear), [
            'year' => $year,
            'start_date' => $start_date->toDateString(),
            'end_date' => $end_date->toDateString(),
            'status' => $status,
            'current' => $current,
        ]);

        $academicYear->refresh();

        $response->assertRedirect(route('academicYears.index'));
        $response->assertSessionHas('academicYear.id', $academicYear->id);

        $this->assertEquals($year, $academicYear->year);
        $this->assertEquals($start_date, $academicYear->start_date);
        $this->assertEquals($end_date, $academicYear->end_date);
        $this->assertEquals($status, $academicYear->status);
        $this->assertEquals($current, $academicYear->current);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $academicYear = AcademicYear::factory()->create();

        $response = $this->delete(route('academic-years.destroy', $academicYear));

        $response->assertRedirect(route('academicYears.index'));

        $this->assertSoftDeleted($academicYear);
    }
}
