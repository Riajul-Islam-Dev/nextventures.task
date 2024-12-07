<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        Product::create([
            'name' => 'Laravel Book',
            'description' => 'Comprehensive guide to Laravel framework.',
            'price' => 50.00,
            'stock' => 10,
        ]);

        for ($i = 0; $i < 19; $i++) {
            Product::create([
                'name' => $faker->word() . ' Product',
                'description' => $faker->sentence(),
                'price' => $faker->randomFloat(2, 5, 100),
                'stock' => $faker->numberBetween(1, 50),
            ]);
        }
    }
}
