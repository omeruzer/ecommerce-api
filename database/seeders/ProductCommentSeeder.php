<?php

namespace Database\Seeders;

use App\Models\ProductComment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 100; $i++) {
            ProductComment::create([
                'user_id' => rand(1, 10),
                'product_id' => rand(1, 50),
                'comment' => 'comment_' . ($i + 1),
            ]);
        }
    }
}
