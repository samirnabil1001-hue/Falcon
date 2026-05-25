<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CustomerFollowUp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class CustomerFollowUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = [1, 2, 3];
        
        // 1. التأكد من وجود المستخدمين
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

        // 2. توليد 100 سجل متابعة بتواريخ موزعة على الشهر الحالي
        CustomerFollowUp::factory()->count(100)->create([
            'created_at' => function () {
                // تحديد بداية الشهر الحالي
                $startOfMonth = Carbon::now()->startOfMonth();
                // تحديد وقت "الآن" كحد أقصى
                $now = Carbon::now();
                
                // توليد تاريخ عشوائي بين بداية الشهر والنهاردة
                return Carbon::createFromTimestamp(rand($startOfMonth->timestamp, $now->timestamp));
            },
            'updated_at' => function (array $attributes) {
                // جعل تاريخ التحديث هو نفسه تاريخ الإنشاء عشان يبان منطقي
                return $attributes['created_at'];
            },
        ]);

        // 3. طباعة رسالة نجاح
        $this->command->info('Success: 100 Customer follow-ups created (spread across the current month).');
    }
}