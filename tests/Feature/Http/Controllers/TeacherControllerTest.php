<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TeacherController
 */
final class TeacherControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $teachers = Teacher::factory()->count(3)->create();

        $response = $this->get(route('teachers.index'));

        $response->assertOk();
        $response->assertViewIs('teacher.index');
        $response->assertViewHas('teachers');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('teachers.create'));

        $response->assertOk();
        $response->assertViewIs('teacher.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TeacherController::class,
            'store',
            \App\Http\Requests\TeacherStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $employee_id = $this->faker->word();
        $first_name = $this->faker->firstName();
        $last_name = $this->faker->lastName();
        $email = $this->faker->safeEmail();
        $phone = $this->faker->phoneNumber();
        $address = $this->faker->text();
        $gender = $this->faker->word();
        $birth_date = Carbon::parse($this->faker->date());
        $nationality = $this->faker->word();
        $joining_date = Carbon::parse($this->faker->date());
        $contract_type = $this->faker->word();
        $employment_status = $this->faker->word();
        $qualification = $this->faker->word();
        $specialization = $this->faker->word();
        $experience_years = $this->faker->numberBetween(-10000, 10000);
        $department = Department::factory()->create();
        $position = $this->faker->word();
        $salary_grade = $this->faker->word();
        $emergency_contact_name = $this->faker->word();
        $emergency_contact_phone = $this->faker->word();

        $response = $this->post(route('teachers.store'), [
            'employee_id' => $employee_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'gender' => $gender,
            'birth_date' => $birth_date->toDateString(),
            'nationality' => $nationality,
            'joining_date' => $joining_date->toDateString(),
            'contract_type' => $contract_type,
            'employment_status' => $employment_status,
            'qualification' => $qualification,
            'specialization' => $specialization,
            'experience_years' => $experience_years,
            'department_id' => $department->id,
            'position' => $position,
            'salary_grade' => $salary_grade,
            'emergency_contact_name' => $emergency_contact_name,
            'emergency_contact_phone' => $emergency_contact_phone,
        ]);

        $teachers = Teacher::query()
            ->where('employee_id', $employee_id)
            ->where('first_name', $first_name)
            ->where('last_name', $last_name)
            ->where('email', $email)
            ->where('phone', $phone)
            ->where('address', $address)
            ->where('gender', $gender)
            ->where('birth_date', $birth_date)
            ->where('nationality', $nationality)
            ->where('joining_date', $joining_date)
            ->where('contract_type', $contract_type)
            ->where('employment_status', $employment_status)
            ->where('qualification', $qualification)
            ->where('specialization', $specialization)
            ->where('experience_years', $experience_years)
            ->where('department_id', $department->id)
            ->where('position', $position)
            ->where('salary_grade', $salary_grade)
            ->where('emergency_contact_name', $emergency_contact_name)
            ->where('emergency_contact_phone', $emergency_contact_phone)
            ->get();
        $this->assertCount(1, $teachers);
        $teacher = $teachers->first();

        $response->assertRedirect(route('teachers.index'));
        $response->assertSessionHas('teacher.id', $teacher->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $teacher = Teacher::factory()->create();

        $response = $this->get(route('teachers.show', $teacher));

        $response->assertOk();
        $response->assertViewIs('teacher.show');
        $response->assertViewHas('teacher');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $teacher = Teacher::factory()->create();

        $response = $this->get(route('teachers.edit', $teacher));

        $response->assertOk();
        $response->assertViewIs('teacher.edit');
        $response->assertViewHas('teacher');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TeacherController::class,
            'update',
            \App\Http\Requests\TeacherUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $teacher = Teacher::factory()->create();
        $employee_id = $this->faker->word();
        $first_name = $this->faker->firstName();
        $last_name = $this->faker->lastName();
        $email = $this->faker->safeEmail();
        $phone = $this->faker->phoneNumber();
        $address = $this->faker->text();
        $gender = $this->faker->word();
        $birth_date = Carbon::parse($this->faker->date());
        $nationality = $this->faker->word();
        $joining_date = Carbon::parse($this->faker->date());
        $contract_type = $this->faker->word();
        $employment_status = $this->faker->word();
        $qualification = $this->faker->word();
        $specialization = $this->faker->word();
        $experience_years = $this->faker->numberBetween(-10000, 10000);
        $department = Department::factory()->create();
        $position = $this->faker->word();
        $salary_grade = $this->faker->word();
        $emergency_contact_name = $this->faker->word();
        $emergency_contact_phone = $this->faker->word();

        $response = $this->put(route('teachers.update', $teacher), [
            'employee_id' => $employee_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'gender' => $gender,
            'birth_date' => $birth_date->toDateString(),
            'nationality' => $nationality,
            'joining_date' => $joining_date->toDateString(),
            'contract_type' => $contract_type,
            'employment_status' => $employment_status,
            'qualification' => $qualification,
            'specialization' => $specialization,
            'experience_years' => $experience_years,
            'department_id' => $department->id,
            'position' => $position,
            'salary_grade' => $salary_grade,
            'emergency_contact_name' => $emergency_contact_name,
            'emergency_contact_phone' => $emergency_contact_phone,
        ]);

        $teacher->refresh();

        $response->assertRedirect(route('teachers.index'));
        $response->assertSessionHas('teacher.id', $teacher->id);

        $this->assertEquals($employee_id, $teacher->employee_id);
        $this->assertEquals($first_name, $teacher->first_name);
        $this->assertEquals($last_name, $teacher->last_name);
        $this->assertEquals($email, $teacher->email);
        $this->assertEquals($phone, $teacher->phone);
        $this->assertEquals($address, $teacher->address);
        $this->assertEquals($gender, $teacher->gender);
        $this->assertEquals($birth_date, $teacher->birth_date);
        $this->assertEquals($nationality, $teacher->nationality);
        $this->assertEquals($joining_date, $teacher->joining_date);
        $this->assertEquals($contract_type, $teacher->contract_type);
        $this->assertEquals($employment_status, $teacher->employment_status);
        $this->assertEquals($qualification, $teacher->qualification);
        $this->assertEquals($specialization, $teacher->specialization);
        $this->assertEquals($experience_years, $teacher->experience_years);
        $this->assertEquals($department->id, $teacher->department_id);
        $this->assertEquals($position, $teacher->position);
        $this->assertEquals($salary_grade, $teacher->salary_grade);
        $this->assertEquals($emergency_contact_name, $teacher->emergency_contact_name);
        $this->assertEquals($emergency_contact_phone, $teacher->emergency_contact_phone);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $teacher = Teacher::factory()->create();

        $response = $this->delete(route('teachers.destroy', $teacher));

        $response->assertRedirect(route('teachers.index'));

        $this->assertSoftDeleted($teacher);
    }
}
