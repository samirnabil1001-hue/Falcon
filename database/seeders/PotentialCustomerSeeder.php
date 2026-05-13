<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PotentialCustomer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PotentialCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = [1, 2, 3];
        
        foreach ($userIds as $id) {
            User::firstOrCreate(
                ['id' => $id],
                [
                    'name' => "Staff Member $id",
                    'email' => "staff$id@example.com",
                    'password' => Hash::make('password'), 
                    'email_verified_at' => now(),
                ]
            );
        }
        PotentialCustomer::factory()->count(100)->create();

        $this->command->info('Success: 100 Potential customers created and linked to users 1, 2, and 3.');
    }
}