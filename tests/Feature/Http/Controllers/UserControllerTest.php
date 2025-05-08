<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\UserController
 */
final class UserControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $users = User::factory()->count(3)->create();

        $response = $this->get(route('users.index'));

        $response->assertOk();
        $response->assertViewIs('user.index');
        $response->assertViewHas('users');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('users.create'));

        $response->assertOk();
        $response->assertViewIs('user.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UserController::class,
            'store',
            \App\Http\Requests\UserStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $first_name = $this->faker->firstName();
        $last_name = $this->faker->lastName();
        $email = $this->faker->safeEmail();
        $password = $this->faker->password();
        $phone = $this->faker->phoneNumber();
        $address = $this->faker->text();
        $city = $this->faker->city();
        $country = $this->faker->country();
        $gender = $this->faker->word();
        $birth_date = Carbon::parse($this->faker->date());
        $status = $this->faker->word();

        $response = $this->post(route('users.store'), [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'address' => $address,
            'city' => $city,
            'country' => $country,
            'gender' => $gender,
            'birth_date' => $birth_date->toDateString(),
            'status' => $status,
        ]);

        $users = User::query()
            ->where('first_name', $first_name)
            ->where('last_name', $last_name)
            ->where('email', $email)
            ->where('password', $password)
            ->where('phone', $phone)
            ->where('address', $address)
            ->where('city', $city)
            ->where('country', $country)
            ->where('gender', $gender)
            ->where('birth_date', $birth_date)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $users);
        $user = $users->first();

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('user.id', $user->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('users.show', $user));

        $response->assertOk();
        $response->assertViewIs('user.show');
        $response->assertViewHas('user');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('users.edit', $user));

        $response->assertOk();
        $response->assertViewIs('user.edit');
        $response->assertViewHas('user');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UserController::class,
            'update',
            \App\Http\Requests\UserUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $user = User::factory()->create();
        $first_name = $this->faker->firstName();
        $last_name = $this->faker->lastName();
        $email = $this->faker->safeEmail();
        $password = $this->faker->password();
        $phone = $this->faker->phoneNumber();
        $address = $this->faker->text();
        $city = $this->faker->city();
        $country = $this->faker->country();
        $gender = $this->faker->word();
        $birth_date = Carbon::parse($this->faker->date());
        $status = $this->faker->word();

        $response = $this->put(route('users.update', $user), [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'address' => $address,
            'city' => $city,
            'country' => $country,
            'gender' => $gender,
            'birth_date' => $birth_date->toDateString(),
            'status' => $status,
        ]);

        $user->refresh();

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('user.id', $user->id);

        $this->assertEquals($first_name, $user->first_name);
        $this->assertEquals($last_name, $user->last_name);
        $this->assertEquals($email, $user->email);
        $this->assertEquals($password, $user->password);
        $this->assertEquals($phone, $user->phone);
        $this->assertEquals($address, $user->address);
        $this->assertEquals($city, $user->city);
        $this->assertEquals($country, $user->country);
        $this->assertEquals($gender, $user->gender);
        $this->assertEquals($birth_date, $user->birth_date);
        $this->assertEquals($status, $user->status);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));

        $this->assertSoftDeleted($user);
    }
}
