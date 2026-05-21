<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PotentialCustomerService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PotentialCustomerServiceSeeder extends Seeder
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

        // 2. توليد 100 سجل خدمات عملاء محتملين باستخدام الفاكتوري المجهز
        PotentialCustomerService::factory()->count(100)->create();

        // 3. طباعة رسالة نجاح في الـ Terminal
        $this->command->info('Success: 100 Potential customer services records created and linked to users 1, 2, and 3.');
    }
}