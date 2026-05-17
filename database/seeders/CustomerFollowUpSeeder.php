<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CustomerFollowUp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerFollowUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = [1, 2, 3];
        
        // 1. التأكد من وجود المستخدمين 1 و 2 و 3 لمنع أخطاء الـ Foreign Keys
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

        // 2. توليد 100 سجل متابعة باستخدام الفاكتوري الذي قمنا بإنشائه سابقاً
        CustomerFollowUp::factory()->count(100)->create();

        // 3. طباعة رسالة نجاح في الـ Terminal
        $this->command->info('Success: 100 Customer follow-ups created and assigned to users 1, 2, and 3.');
    }
}