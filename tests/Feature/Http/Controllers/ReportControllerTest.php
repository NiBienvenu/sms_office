<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\GeneratedBy;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ReportController
 */
final class ReportControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $reports = Report::factory()->count(3)->create();

        $response = $this->get(route('reports.index'));

        $response->assertOk();
        $response->assertViewIs('report.index');
        $response->assertViewHas('reports');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('reports.create'));

        $response->assertOk();
        $response->assertViewIs('report.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ReportController::class,
            'store',
            \App\Http\Requests\ReportStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $title = $this->faker->sentence(4);
        $type = $this->faker->word();
        $academic_year = AcademicYear::factory()->create();
        $generated_by = GeneratedBy::factory()->create();
        $status = $this->faker->word();
        $generator = User::factory()->create();

        $response = $this->post(route('reports.store'), [
            'title' => $title,
            'type' => $type,
            'academic_year_id' => $academic_year->id,
            'generated_by' => $generated_by->id,
            'status' => $status,
            'generator_id' => $generator->id,
        ]);

        $reports = Report::query()
            ->where('title', $title)
            ->where('type', $type)
            ->where('academic_year_id', $academic_year->id)
            ->where('generated_by', $generated_by->id)
            ->where('status', $status)
            ->where('generator_id', $generator->id)
            ->get();
        $this->assertCount(1, $reports);
        $report = $reports->first();

        $response->assertRedirect(route('reports.index'));
        $response->assertSessionHas('report.id', $report->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $report = Report::factory()->create();

        $response = $this->get(route('reports.show', $report));

        $response->assertOk();
        $response->assertViewIs('report.show');
        $response->assertViewHas('report');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $report = Report::factory()->create();

        $response = $this->get(route('reports.edit', $report));

        $response->assertOk();
        $response->assertViewIs('report.edit');
        $response->assertViewHas('report');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ReportController::class,
            'update',
            \App\Http\Requests\ReportUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $report = Report::factory()->create();
        $title = $this->faker->sentence(4);
        $type = $this->faker->word();
        $academic_year = AcademicYear::factory()->create();
        $generated_by = GeneratedBy::factory()->create();
        $status = $this->faker->word();
        $generator = User::factory()->create();

        $response = $this->put(route('reports.update', $report), [
            'title' => $title,
            'type' => $type,
            'academic_year_id' => $academic_year->id,
            'generated_by' => $generated_by->id,
            'status' => $status,
            'generator_id' => $generator->id,
        ]);

        $report->refresh();

        $response->assertRedirect(route('reports.index'));
        $response->assertSessionHas('report.id', $report->id);

        $this->assertEquals($title, $report->title);
        $this->assertEquals($type, $report->type);
        $this->assertEquals($academic_year->id, $report->academic_year_id);
        $this->assertEquals($generated_by->id, $report->generated_by);
        $this->assertEquals($status, $report->status);
        $this->assertEquals($generator->id, $report->generator_id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $report = Report::factory()->create();

        $response = $this->delete(route('reports.destroy', $report));

        $response->assertRedirect(route('reports.index'));

        $this->assertSoftDeleted($report);
    }
}
