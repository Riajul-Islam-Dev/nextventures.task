<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'product_id' => 1, // Replace with a valid product ID if needed
            'quantity' => $this->faker->numberBetween(1, 10),
            'total_price' => $this->faker->numberBetween(100, 10000),
            'status' => 0, // Adjust the initial status as needed
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
