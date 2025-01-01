<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Insert multiple products using Faker
        foreach (range(1, 5) as $index) {
            DB::table('products')->insert([
                'title' => $faker->word,
                'sub_title' => $faker->text(10),
                'price' => $faker->randomFloat(2, 5, 500), // Price between 5 and 500 with 2 decimal places
                'image_url' => $faker->imageUrl(),
                'description' => $faker->text(10),
                'discount' => $faker->numberBetween(0, 50), // Random discount between 0% and 50%
                'rating' => $faker->numberBetween(0, 5), // Rating between 0 and 5
                'status' => $faker->randomElement(['Active', 'Inactive']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
