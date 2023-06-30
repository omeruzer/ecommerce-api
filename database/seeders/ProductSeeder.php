<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 50; $i++) {
            $productName = $faker->sentence(2);

            Product::create([
                'name' => $productName,
                'slug' => Str::slug($productName),
                'desc' => $faker->sentence(15),
                'category_id' => rand(1, 14),
                'brand_id' => rand(1, 5),
                'price' => $faker->randomFloat(3, 1, 50),
                'code' => rand(20000, 40000),
                'keywords' => $faker->sentence(3),
                'description' => $faker->sentence(10),
                'quantity' => rand(0, 1000),
            ]);
        }
    }
}
