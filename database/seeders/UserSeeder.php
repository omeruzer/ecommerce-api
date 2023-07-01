<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserInfo;
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

        $admin = User::create([
            'name' => 'Admin admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('asd123'),
            'is_admin' => 1
        ]);

        UserInfo::create([
            'user_id' => $admin->id,
            'address' => 'Test Adres',
            'postal_code' => '1234',
            'city' => 'İstanbul',
            'country' => 'Turkey',
            'phone' => '2131321213',
        ]);

        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {

            $user = User::create([
                'name' => $faker->name(),
                'email' => $faker->email(),
                'password' => Hash::make('asd123'),
            ]);


            UserInfo::create([
                'user_id' => $user->id,
                'address' => $faker->address(),
                'postal_code' => $faker->postcode(),
                'city' => $faker->city(),
                'country' => $faker->country(),
                'phone' => $faker->phoneNumber(),
            ]);
        }
    }
}
