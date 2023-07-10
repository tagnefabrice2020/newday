<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $roles = DB::table('roles')->pluck('id');

        foreach (range(1, 100) as $index) {
            DB::table('users')->insert([
                'uuid' => Str::orderedUuid(),
                'name' => $faker->name,
                'username' => $faker->userName,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'role_id' => $faker->randomElement($roles),
                'country_id' => null,
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
