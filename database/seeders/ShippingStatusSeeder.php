<?php

namespace Database\Seeders;

use App\Models\ShippingStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'pending',
            'processing',
            'shipped',
            'delivered',
            'cancelled',
            'refunded',
            'on hold',
            'completed',
            'failed',
        ];

        foreach ($statuses as $key => $status) {
            ShippingStatus::create([
                'title' => $status
            ]);
        }
    }
}
