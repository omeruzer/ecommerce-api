<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i=0; $i < 30 ; $i++) {

            $title      = $faker->sentence(3);
            $keyw       = $faker->sentence(5);
            $desc       = $faker->sentence(10);
            $content    = $faker->sentence(100);


            Blog::create([
                'image'       =>  "sample_". $i+1 .".jpg",
                'title'     =>  $title,
                'slug'      =>  Str::slug($title),
                'description'      =>  $desc,
                'keywords'      =>  $keyw,
                'content'   =>  $content
            ]);
        }
    }
}
