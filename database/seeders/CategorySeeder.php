<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Home & Kitchen',
                'children' => [
                    ['name' => 'Garden'],
                    ['name' => 'Bath'],
                    ['name' => 'Kids'],
                ]
            ],
            [
                'name' => 'Electronics',
                'children' => [
                    ['name' => 'Computers'],
                    ['name' => 'Camera & Photo'],
                    ['name' => 'Video Games'],
                    ['name' => 'Phones'],
                ]
            ],
            [
                'name' => 'Fashions',
                'children' => [
                    ['name' => 'Men'],
                    ['name' => 'Women'],
                    ['name' => 'Kids'],
                    ['name' => 'Baby'],
                ]
            ],

        ];

        foreach ($categories as $key => $category) {
            $mainCategory = Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name'])
            ]);
            foreach ($category['children'] as $key => $subCategory) {

                Category::create([
                    'name' => $subCategory['name'],
                    'slug' => Str::slug($subCategory['name']),
                    'parent_id' => $mainCategory['id']
                ]);

            }
        }
        
    }
}
