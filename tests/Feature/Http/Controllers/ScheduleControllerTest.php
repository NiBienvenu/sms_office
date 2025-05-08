<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ScheduleController
 */
final class ScheduleControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $schedules = Schedule::factory()->count(3)->create();

        $response = $this->get(route('schedules.index'));

        $response->assertOk();
        $response->assertViewIs('schedule.index');
        $response->assertViewHas('schedules');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('schedules.create'));

        $response->assertOk();
        $response->assertViewIs('schedule.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ScheduleController::class,
            'store',
            \App\Http\Requests\ScheduleStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $course = Course::factory()->create();
        $teacher = Teacher::factory()->create();
        $academic_year = AcademicYear::factory()->create();
        $day_of_week = $this->faker->word();
        $start_time = $this->faker->time();
        $end_time = $this->faker->time();
        $room = $this->faker->word();

        $response = $this->post(route('schedules.store'), [
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
            'academic_year_id' => $academic_year->id,
            'day_of_week' => $day_of_week,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'room' => $room,
        ]);

        $schedules = Schedule::query()
            ->where('course_id', $course->id)
            ->where('teacher_id', $teacher->id)
            ->where('academic_year_id', $academic_year->id)
            ->where('day_of_week', $day_of_week)
            ->where('start_time', $start_time)
            ->where('end_time', $end_time)
            ->where('room', $room)
            ->get();
        $this->assertCount(1, $schedules);
        $schedule = $schedules->first();

        $response->assertRedirect(route('schedules.index'));
        $response->assertSessionHas('schedule.id', $schedule->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $schedule = Schedule::factory()->create();

        $response = $this->get(route('schedules.show', $schedule));

        $response->assertOk();
        $response->assertViewIs('schedule.show');
        $response->assertViewHas('schedule');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $schedule = Schedule::factory()->create();

        $response = $this->get(route('schedules.edit', $schedule));

        $response->assertOk();
        $response->assertViewIs('schedule.edit');
        $response->assertViewHas('schedule');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ScheduleController::class,
            'update',
            \App\Http\Requests\ScheduleUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $schedule = Schedule::factory()->create();
        $course = Course::factory()->create();
        $teacher = Teacher::factory()->create();
        $academic_year = AcademicYear::factory()->create();
        $day_of_week = $this->faker->word();
        $start_time = $this->faker->time();
        $end_time = $this->faker->time();
        $room = $this->faker->word();

        $response = $this->put(route('schedules.update', $schedule), [
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
            'academic_year_id' => $academic_year->id,
            'day_of_week' => $day_of_week,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'room' => $room,
        ]);

        $schedule->refresh();

        $response->assertRedirect(route('schedules.index'));
        $response->assertSessionHas('schedule.id', $schedule->id);

        $this->assertEquals($course->id, $schedule->course_id);
        $this->assertEquals($teacher->id, $schedule->teacher_id);
        $this->assertEquals($academic_year->id, $schedule->academic_year_id);
        $this->assertEquals($day_of_week, $schedule->day_of_week);
        $this->assertEquals($start_time, $schedule->start_time);
        $this->assertEquals($end_time, $schedule->end_time);
        $this->assertEquals($room, $schedule->room);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $schedule = Schedule::factory()->create();

        $response = $this->delete(route('schedules.destroy', $schedule));

        $response->assertRedirect(route('schedules.index'));

        $this->assertSoftDeleted($schedule);
    }
}
