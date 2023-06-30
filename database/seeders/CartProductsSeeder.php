<?php

namespace Database\Seeders;

use App\Models\CartProducts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 8; $i++) {
            CartProducts::create([
                'cart_id' => $i + 1,
                'product_id' => rand(1, 50),
                'quantity' => rand(1, 5)
            ]);
        }
    }
}
