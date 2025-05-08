<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PaymentController
 */
final class PaymentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $payments = Payment::factory()->count(3)->create();

        $response = $this->get(route('payments.index'));

        $response->assertOk();
        $response->assertViewIs('payment.index');
        $response->assertViewHas('payments');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('payments.create'));

        $response->assertOk();
        $response->assertViewIs('payment.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PaymentController::class,
            'store',
            \App\Http\Requests\PaymentStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $student = Student::factory()->create();
        $academic_year = AcademicYear::factory()->create();
        $amount = $this->faker->randomFloat(/** decimal_attributes **/);
        $payment_type = $this->faker->word();
        $payment_date = Carbon::parse($this->faker->date());
        $status = $this->faker->word();
        $reference_number = $this->faker->word();
        $semester = $this->faker->word();

        $response = $this->post(route('payments.store'), [
            'student_id' => $student->id,
            'academic_year_id' => $academic_year->id,
            'amount' => $amount,
            'payment_type' => $payment_type,
            'payment_date' => $payment_date->toDateString(),
            'status' => $status,
            'reference_number' => $reference_number,
            'semester' => $semester,
        ]);

        $payments = Payment::query()
            ->where('student_id', $student->id)
            ->where('academic_year_id', $academic_year->id)
            ->where('amount', $amount)
            ->where('payment_type', $payment_type)
            ->where('payment_date', $payment_date)
            ->where('status', $status)
            ->where('reference_number', $reference_number)
            ->where('semester', $semester)
            ->get();
        $this->assertCount(1, $payments);
        $payment = $payments->first();

        $response->assertRedirect(route('payments.index'));
        $response->assertSessionHas('payment.id', $payment->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->get(route('payments.show', $payment));

        $response->assertOk();
        $response->assertViewIs('payment.show');
        $response->assertViewHas('payment');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->get(route('payments.edit', $payment));

        $response->assertOk();
        $response->assertViewIs('payment.edit');
        $response->assertViewHas('payment');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PaymentController::class,
            'update',
            \App\Http\Requests\PaymentUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $payment = Payment::factory()->create();
        $student = Student::factory()->create();
        $academic_year = AcademicYear::factory()->create();
        $amount = $this->faker->randomFloat(/** decimal_attributes **/);
        $payment_type = $this->faker->word();
        $payment_date = Carbon::parse($this->faker->date());
        $status = $this->faker->word();
        $reference_number = $this->faker->word();
        $semester = $this->faker->word();

        $response = $this->put(route('payments.update', $payment), [
            'student_id' => $student->id,
            'academic_year_id' => $academic_year->id,
            'amount' => $amount,
            'payment_type' => $payment_type,
            'payment_date' => $payment_date->toDateString(),
            'status' => $status,
            'reference_number' => $reference_number,
            'semester' => $semester,
        ]);

        $payment->refresh();

        $response->assertRedirect(route('payments.index'));
        $response->assertSessionHas('payment.id', $payment->id);

        $this->assertEquals($student->id, $payment->student_id);
        $this->assertEquals($academic_year->id, $payment->academic_year_id);
        $this->assertEquals($amount, $payment->amount);
        $this->assertEquals($payment_type, $payment->payment_type);
        $this->assertEquals($payment_date, $payment->payment_date);
        $this->assertEquals($status, $payment->status);
        $this->assertEquals($reference_number, $payment->reference_number);
        $this->assertEquals($semester, $payment->semester);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->delete(route('payments.destroy', $payment));

        $response->assertRedirect(route('payments.index'));

        $this->assertSoftDeleted($payment);
    }
}
