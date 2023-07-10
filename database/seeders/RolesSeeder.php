<?php

namespace Database\Seeders;


use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'uuid' => Str::orderedUuid(),
                'name' => 'superadmin',
                'description' => 'Super Admin Role',
                'status' => true,
            ],
            [
                'uuid' => Str::orderedUuid(),
                'name' => 'admin',
                'description' => 'Admin Role',
                'status' => true,
            ],
            [
                'uuid' => Str::orderedUuid(),
                'name' => 'setters',
                'description' => 'Setters Role',
                'status' => true,
            ],
            [
                'uuid' => Str::orderedUuid(),
                'name' => 'users',
                'description' => 'Users Role',
                'status' => true,
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
