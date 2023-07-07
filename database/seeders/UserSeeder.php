<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'مریم سوری',
            'email' => 'maryam@gmail.com',
            'mobile' => '09307347918',
            'password' => Hash::make('123456789'),
            'is_active' => true,
            // 'api_token' => Hash::make('987654321'),
        ]);
    }
}
