<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        

        // Then create users linked to customers
        $users = [
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => Hash::make('12345678'),
                'phone' => '0123456789',
                'type' => 'user',
                'status' => 'active',
                // 'address' => 'takeo',
                // 'country' => 'Cambodia',
                // 'city' => 'Takeo',
            
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
                'phone' => '0987654321',
                'type' => 'admin',
                'status' => 'active',
                // 'address' => 'takeo',
                // 'country' => 'Cambodia',
                // 'city' => 'Takeo',
                
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert users into database - use 'tbl_users' table
        DB::table('users')->insert($users);
    }
}