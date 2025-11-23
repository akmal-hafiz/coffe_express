<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin Coffee',
            'email' => 'admin@coffee.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create Test Regular User
        User::create([
            'name' => 'John Doe',
            'email' => 'user@coffee.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
    }
}
