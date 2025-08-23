<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emily Johnson',
                'email' => 'manager@example.com',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Michael Williams',
                'email' => 'pegawai@example.com',
                'password' => Hash::make('password123'),
                'role' => 'pegawai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
