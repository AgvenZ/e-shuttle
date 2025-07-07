<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin Account
        User::create([
            'name' => 'Admin E-Shuttle',
            'email' => 'admin@eshuttle.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Create User Account
        User::create([
            'name' => 'User E-Shuttle',
            'email' => 'user@eshuttle.com', 
            'password' => Hash::make('user123'),
            'role' => 'user'
        ]);
    }
}
