<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Admin admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('asd123'),
            'is_admin' => 1
        ]);

        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {

            User::create([
                'name' => $faker->name(),
                'email' => $faker->email(),
                'password' => Hash::make('asd123'),
            ]);
        }
    }
}
