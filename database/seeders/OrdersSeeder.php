<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        $productIds = DB::table('products')->pluck('id')->toArray();

        $userIds = User::role('User')->pluck('id')->toArray();

        if (empty($productIds)) {
            $this->command->info('No products found. Please seed the products table first.');
            return;
        }

        if (empty($userIds)) {
            $this->command->info('No users with role "User" found. Please seed the users table first.');
            return;
        }

        // Seed orders
        for ($i = 0; $i < 50; $i++) {
            $productId = $faker->randomElement($productIds);
            $quantity = $faker->numberBetween(1, 10);

            $product = DB::table('products')->find($productId);

            if ($product && $product->stock >= $quantity) {
                $totalPrice = $product->price * $quantity;

                Order::create([
                    'user_id' => $faker->randomElement($userIds),
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'total_price' => $totalPrice,
                    'status' => $faker->randomElement([0, 1]), // 0 = pending, 1 = completed
                    'created_at' => now()->subDays(rand(0, 30)),
                    'updated_at' => now()->subDays(rand(0, 30)),
                ]);

                // Update product stock
                DB::table('products')->where('id', $productId)->decrement('stock', $quantity);
            }
        }
    }
}
