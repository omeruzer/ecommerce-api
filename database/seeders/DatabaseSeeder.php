<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Cart;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(BlogSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductCommentSeeder::class);
        $this->call(CartSeeder::class);
        $this->call(CartProductsSeeder::class);
        $this->call(ShippingSeeder::class);
        $this->call(ShippingStatusSeeder::class);
    }
}
