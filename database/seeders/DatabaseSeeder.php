<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\UserRole;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'CEO User',
            'email' => 'ceo@example.com',
            'password' => bcrypt('Demo@2020'),
            'is_active' => true,
            'email_verified_at' => now(),
            'role' => UserRole::CEO,
        ]);

        User::factory(50)->create(); 
    }
}