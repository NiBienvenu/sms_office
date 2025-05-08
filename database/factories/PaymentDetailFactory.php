<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Payment;
use App\Models\PaymentDetail;

class PaymentDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentDetail::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
            'fee_type' => $this->faker->word(),
            'amount' => $this->faker->randomFloat(2, 0, 99999999.99),
            'description' => $this->faker->text(),
        ];
    }
}
