<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'product_name' => $this->faker->word, // Adjust if you need more realistic product names
            'amount' => $this->faker->numberBetween(100, 10000),
            'status' => 0, // Use integer values (0 for 'pending', 1 for 'success', 2 for 'failure')
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
