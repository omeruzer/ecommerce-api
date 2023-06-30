<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 100; $i++) {
            ProductImage::create([
                'product_id' => rand(1, 50),
                'image' => "sample_" . $i + 1 . ".jpg",
            ]);
        }
    }
}
