<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carts = [2, 3, 4, 5, 6, 7, 8, 9];

        foreach ($carts as $key => $cart) {
            Cart::create([
                'user_id' => $cart
            ]);
        }
    }
}
