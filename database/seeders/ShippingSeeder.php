<?php

namespace Database\Seeders;

use App\Models\Shipping;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shipping::create(['price' => 45]);
    }
}
