<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ClassRoomController
 */
final class ClassRoomControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $classRooms = ClassRoom::factory()->count(3)->create();

        $response = $this->get(route('class-rooms.index'));

        $response->assertOk();
        $response->assertViewIs('classRoom.index');
        $response->assertViewHas('classRooms');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('class-rooms.create'));

        $response->assertOk();
        $response->assertViewIs('classRoom.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ClassRoomController::class,
            'store',
            \App\Http\Requests\ClassRoomStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = fake()->name();
        $code = fake()->word();
        $level = fake()->word();
        $capacity = fake()->numberBetween(-10000, 10000);
        $student_count = fake()->numberBetween(-10000, 10000);

        $response = $this->post(route('class-rooms.store'), [
            'name' => $name,
            'code' => $code,
            'level' => $level,
            'capacity' => $capacity,
            'student_count' => $student_count,
        ]);

        $classRooms = ClassRoom::query()
            ->where('name', $name)
            ->where('code', $code)
            ->where('level', $level)
            ->where('capacity', $capacity)
            ->where('student_count', $student_count)
            ->get();
        $this->assertCount(1, $classRooms);
        $classRoom = $classRooms->first();

        $response->assertRedirect(route('classRooms.index'));
        $response->assertSessionHas('classRoom.id', $classRoom->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $classRoom = ClassRoom::factory()->create();

        $response = $this->get(route('class-rooms.show', $classRoom));

        $response->assertOk();
        $response->assertViewIs('classRoom.show');
        $response->assertViewHas('classRoom');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $classRoom = ClassRoom::factory()->create();

        $response = $this->get(route('class-rooms.edit', $classRoom));

        $response->assertOk();
        $response->assertViewIs('classRoom.edit');
        $response->assertViewHas('classRoom');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ClassRoomController::class,
            'update',
            \App\Http\Requests\ClassRoomUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $classRoom = ClassRoom::factory()->create();
        $name = fake()->name();
        $code = fake()->word();
        $level = fake()->word();
        $capacity = fake()->numberBetween(-10000, 10000);
        $student_count = fake()->numberBetween(-10000, 10000);

        $response = $this->put(route('class-rooms.update', $classRoom), [
            'name' => $name,
            'code' => $code,
            'level' => $level,
            'capacity' => $capacity,
            'student_count' => $student_count,
        ]);

        $classRoom->refresh();

        $response->assertRedirect(route('classRooms.index'));
        $response->assertSessionHas('classRoom.id', $classRoom->id);

        $this->assertEquals($name, $classRoom->name);
        $this->assertEquals($code, $classRoom->code);
        $this->assertEquals($level, $classRoom->level);
        $this->assertEquals($capacity, $classRoom->capacity);
        $this->assertEquals($student_count, $classRoom->student_count);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $classRoom = ClassRoom::factory()->create();

        $response = $this->delete(route('class-rooms.destroy', $classRoom));

        $response->assertRedirect(route('classRooms.index'));

        $this->assertSoftDeleted($classRoom);
    }
}
