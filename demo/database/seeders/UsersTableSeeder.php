<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // for ($i = 0; $i < 10; $i++) { // Adjust the number of users as needed
        //     DB::table('users')->insert([
        //         'name' => $faker->name(),
        //         'password' => '1234', // Replace 'password' with a desired default password
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        DB::table('users')->insert([
            'name' => 'admin',
            'password' => Hash::make('1234'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'user',
            'password' => Hash::make('1234'),
            'role' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
