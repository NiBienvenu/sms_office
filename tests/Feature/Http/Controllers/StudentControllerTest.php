<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\StudentController
 */
final class StudentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $students = Student::factory()->count(3)->create();

        $response = $this->get(route('students.index'));

        $response->assertOk();
        $response->assertViewIs('student.index');
        $response->assertViewHas('students');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('students.create'));

        $response->assertOk();
        $response->assertViewIs('student.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\StudentController::class,
            'store',
            \App\Http\Requests\StudentStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $matricule = $this->faker->word();
        $first_name = $this->faker->firstName();
        $last_name = $this->faker->lastName();
        $address = $this->faker->text();
        $gender = $this->faker->word();
        $birth_date = Carbon::parse($this->faker->date());
        $birth_place = $this->faker->word();
        $nationality = $this->faker->word();
        $admission_date = Carbon::parse($this->faker->date());
        $current_class = $this->faker->word();
        $academic_year = AcademicYear::factory()->create();
        $education_level = $this->faker->word();
        $guardian_name = $this->faker->word();
        $guardian_relationship = $this->faker->word();
        $guardian_phone = $this->faker->word();
        $guardian_address = $this->faker->text();
        $guardian_occupation = $this->faker->word();
        $emergency_contact = $this->faker->word();
        $status = $this->faker->word();

        $response = $this->post(route('students.store'), [
            'matricule' => $matricule,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'address' => $address,
            'gender' => $gender,
            'birth_date' => $birth_date->toDateString(),
            'birth_place' => $birth_place,
            'nationality' => $nationality,
            'admission_date' => $admission_date->toDateString(),
            'current_class' => $current_class,
            'academic_year_id' => $academic_year->id,
            'education_level' => $education_level,
            'guardian_name' => $guardian_name,
            'guardian_relationship' => $guardian_relationship,
            'guardian_phone' => $guardian_phone,
            'guardian_address' => $guardian_address,
            'guardian_occupation' => $guardian_occupation,
            'emergency_contact' => $emergency_contact,
            'status' => $status,
        ]);

        $students = Student::query()
            ->where('matricule', $matricule)
            ->where('first_name', $first_name)
            ->where('last_name', $last_name)
            ->where('address', $address)
            ->where('gender', $gender)
            ->where('birth_date', $birth_date)
            ->where('birth_place', $birth_place)
            ->where('nationality', $nationality)
            ->where('admission_date', $admission_date)
            ->where('current_class', $current_class)
            ->where('academic_year_id', $academic_year->id)
            ->where('education_level', $education_level)
            ->where('guardian_name', $guardian_name)
            ->where('guardian_relationship', $guardian_relationship)
            ->where('guardian_phone', $guardian_phone)
            ->where('guardian_address', $guardian_address)
            ->where('guardian_occupation', $guardian_occupation)
            ->where('emergency_contact', $emergency_contact)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $students);
        $student = $students->first();

        $response->assertRedirect(route('students.index'));
        $response->assertSessionHas('student.id', $student->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $student = Student::factory()->create();

        $response = $this->get(route('students.show', $student));

        $response->assertOk();
        $response->assertViewIs('student.show');
        $response->assertViewHas('student');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $student = Student::factory()->create();

        $response = $this->get(route('students.edit', $student));

        $response->assertOk();
        $response->assertViewIs('student.edit');
        $response->assertViewHas('student');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\StudentController::class,
            'update',
            \App\Http\Requests\StudentUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $student = Student::factory()->create();
        $matricule = $this->faker->word();
        $first_name = $this->faker->firstName();
        $last_name = $this->faker->lastName();
        $address = $this->faker->text();
        $gender = $this->faker->word();
        $birth_date = Carbon::parse($this->faker->date());
        $birth_place = $this->faker->word();
        $nationality = $this->faker->word();
        $admission_date = Carbon::parse($this->faker->date());
        $current_class = $this->faker->word();
        $academic_year = AcademicYear::factory()->create();
        $education_level = $this->faker->word();
        $guardian_name = $this->faker->word();
        $guardian_relationship = $this->faker->word();
        $guardian_phone = $this->faker->word();
        $guardian_address = $this->faker->text();
        $guardian_occupation = $this->faker->word();
        $emergency_contact = $this->faker->word();
        $status = $this->faker->word();

        $response = $this->put(route('students.update', $student), [
            'matricule' => $matricule,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'address' => $address,
            'gender' => $gender,
            'birth_date' => $birth_date->toDateString(),
            'birth_place' => $birth_place,
            'nationality' => $nationality,
            'admission_date' => $admission_date->toDateString(),
            'current_class' => $current_class,
            'academic_year_id' => $academic_year->id,
            'education_level' => $education_level,
            'guardian_name' => $guardian_name,
            'guardian_relationship' => $guardian_relationship,
            'guardian_phone' => $guardian_phone,
            'guardian_address' => $guardian_address,
            'guardian_occupation' => $guardian_occupation,
            'emergency_contact' => $emergency_contact,
            'status' => $status,
        ]);

        $student->refresh();

        $response->assertRedirect(route('students.index'));
        $response->assertSessionHas('student.id', $student->id);

        $this->assertEquals($matricule, $student->matricule);
        $this->assertEquals($first_name, $student->first_name);
        $this->assertEquals($last_name, $student->last_name);
        $this->assertEquals($address, $student->address);
        $this->assertEquals($gender, $student->gender);
        $this->assertEquals($birth_date, $student->birth_date);
        $this->assertEquals($birth_place, $student->birth_place);
        $this->assertEquals($nationality, $student->nationality);
        $this->assertEquals($admission_date, $student->admission_date);
        $this->assertEquals($current_class, $student->current_class);
        $this->assertEquals($academic_year->id, $student->academic_year_id);
        $this->assertEquals($education_level, $student->education_level);
        $this->assertEquals($guardian_name, $student->guardian_name);
        $this->assertEquals($guardian_relationship, $student->guardian_relationship);
        $this->assertEquals($guardian_phone, $student->guardian_phone);
        $this->assertEquals($guardian_address, $student->guardian_address);
        $this->assertEquals($guardian_occupation, $student->guardian_occupation);
        $this->assertEquals($emergency_contact, $student->emergency_contact);
        $this->assertEquals($status, $student->status);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $student = Student::factory()->create();

        $response = $this->delete(route('students.destroy', $student));

        $response->assertRedirect(route('students.index'));

        $this->assertSoftDeleted($student);
    }
}
