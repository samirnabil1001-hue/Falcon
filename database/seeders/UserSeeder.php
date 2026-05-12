<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. إنشاء حساب الـ CEO
        User::factory()->create([
            'name' => 'CEO Admin',
            'email' => 'ceo@example.com',
            'password' => Hash::make('Demo@2020'),
            'is_active' => true,
            'email_verified_at' => now(),
            'role' => UserRole::CEO,
        ]);

        User::factory()->create([
            'name' => 'Team Lead Account',
            'email' => 'lead@example.com',
            'password' => Hash::make('Demo@2020'),
            'is_active' => true,
            'email_verified_at' => now(),
            'role' => UserRole::TEAM_LEAD,
        ]);

        User::factory()->create([
            'name' => 'Field Agent',
            'email' => 'agent@example.com',
            'password' => Hash::make('Demo@2020'),
            'is_active' => true,
            'email_verified_at' => now(),
            'role' => UserRole::AGENT,
        ]);

        User::factory(50)->create();
    }
}