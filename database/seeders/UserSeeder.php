<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'show_password' => 'admin123',
        ]);
        User::create([
            'username' => 'user',
            'password' => bcrypt('user123'),
            'role' => 'user',
            'show_password' => 'user123',
        ]);
    }
}
