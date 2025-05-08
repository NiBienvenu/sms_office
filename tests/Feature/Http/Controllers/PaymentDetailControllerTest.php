<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PaymentDetailController
 */
final class PaymentDetailControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $paymentDetails = PaymentDetail::factory()->count(3)->create();

        $response = $this->get(route('payment-details.index'));

        $response->assertOk();
        $response->assertViewIs('paymentDetail.index');
        $response->assertViewHas('paymentDetails');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('payment-details.create'));

        $response->assertOk();
        $response->assertViewIs('paymentDetail.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PaymentDetailController::class,
            'store',
            \App\Http\Requests\PaymentDetailStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $payment = Payment::factory()->create();
        $fee_type = $this->faker->word();
        $amount = $this->faker->randomFloat(/** decimal_attributes **/);

        $response = $this->post(route('payment-details.store'), [
            'payment_id' => $payment->id,
            'fee_type' => $fee_type,
            'amount' => $amount,
        ]);

        $paymentDetails = PaymentDetail::query()
            ->where('payment_id', $payment->id)
            ->where('fee_type', $fee_type)
            ->where('amount', $amount)
            ->get();
        $this->assertCount(1, $paymentDetails);
        $paymentDetail = $paymentDetails->first();

        $response->assertRedirect(route('paymentDetails.index'));
        $response->assertSessionHas('paymentDetail.id', $paymentDetail->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $paymentDetail = PaymentDetail::factory()->create();

        $response = $this->get(route('payment-details.show', $paymentDetail));

        $response->assertOk();
        $response->assertViewIs('paymentDetail.show');
        $response->assertViewHas('paymentDetail');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $paymentDetail = PaymentDetail::factory()->create();

        $response = $this->get(route('payment-details.edit', $paymentDetail));

        $response->assertOk();
        $response->assertViewIs('paymentDetail.edit');
        $response->assertViewHas('paymentDetail');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PaymentDetailController::class,
            'update',
            \App\Http\Requests\PaymentDetailUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $paymentDetail = PaymentDetail::factory()->create();
        $payment = Payment::factory()->create();
        $fee_type = $this->faker->word();
        $amount = $this->faker->randomFloat(/** decimal_attributes **/);

        $response = $this->put(route('payment-details.update', $paymentDetail), [
            'payment_id' => $payment->id,
            'fee_type' => $fee_type,
            'amount' => $amount,
        ]);

        $paymentDetail->refresh();

        $response->assertRedirect(route('paymentDetails.index'));
        $response->assertSessionHas('paymentDetail.id', $paymentDetail->id);

        $this->assertEquals($payment->id, $paymentDetail->payment_id);
        $this->assertEquals($fee_type, $paymentDetail->fee_type);
        $this->assertEquals($amount, $paymentDetail->amount);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $paymentDetail = PaymentDetail::factory()->create();

        $response = $this->delete(route('payment-details.destroy', $paymentDetail));

        $response->assertRedirect(route('paymentDetails.index'));

        $this->assertSoftDeleted($paymentDetail);
    }
}
