<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Nike',
            'Adidas',
            'English Home',
            'Samsung',
            'Apple'
        ];

        foreach ($brands as $key => $brand) {
            Brand::create([
                'name' => $brand,
                'slug' => Str::slug($brand)
            ]);
        }
    }
}
