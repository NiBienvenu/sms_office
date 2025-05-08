<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bulletin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BulletinController
 */
final class BulletinControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $bulletins = Bulletin::factory()->count(3)->create();

        $response = $this->get(route('bulletins.index'));

        $response->assertOk();
        $response->assertViewIs('bulletin.index');
        $response->assertViewHas('bulletins');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('bulletins.create'));

        $response->assertOk();
        $response->assertViewIs('bulletin.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BulletinController::class,
            'store',
            \App\Http\Requests\BulletinStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $status = fake()->word();
        $unique = fake()->word();

        $response = $this->post(route('bulletins.store'), [
            'status' => $status,
            'unique' => $unique,
        ]);

        $bulletins = Bulletin::query()
            ->where('status', $status)
            ->where('unique', $unique)
            ->get();
        $this->assertCount(1, $bulletins);
        $bulletin = $bulletins->first();

        $response->assertRedirect(route('bulletins.index'));
        $response->assertSessionHas('bulletin.id', $bulletin->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $bulletin = Bulletin::factory()->create();

        $response = $this->get(route('bulletins.show', $bulletin));

        $response->assertOk();
        $response->assertViewIs('bulletin.show');
        $response->assertViewHas('bulletin');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $bulletin = Bulletin::factory()->create();

        $response = $this->get(route('bulletins.edit', $bulletin));

        $response->assertOk();
        $response->assertViewIs('bulletin.edit');
        $response->assertViewHas('bulletin');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BulletinController::class,
            'update',
            \App\Http\Requests\BulletinUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $bulletin = Bulletin::factory()->create();
        $status = fake()->word();
        $unique = fake()->word();

        $response = $this->put(route('bulletins.update', $bulletin), [
            'status' => $status,
            'unique' => $unique,
        ]);

        $bulletin->refresh();

        $response->assertRedirect(route('bulletins.index'));
        $response->assertSessionHas('bulletin.id', $bulletin->id);

        $this->assertEquals($status, $bulletin->status);
        $this->assertEquals($unique, $bulletin->unique);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $bulletin = Bulletin::factory()->create();

        $response = $this->delete(route('bulletins.destroy', $bulletin));

        $response->assertRedirect(route('bulletins.index'));

        $this->assertModelMissing($bulletin);
    }
}
