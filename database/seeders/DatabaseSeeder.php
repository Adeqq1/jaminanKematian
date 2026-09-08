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
        // User::factory(10)->create();

        User::create([
            'name' => 'Administrator',
            'username' => env('ADMIN_USERNAME', '0000000000000000'),
            'password' => env('ADMIN_PASSWORD', 'change-this-password'),
            'role' => 'admin',
        ]);
    }
}
