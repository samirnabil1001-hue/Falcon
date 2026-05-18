<?php

namespace Database\Factories;

use App\Models\CustomerFollowUp;
use App\Models\PotentialCustomer;
use App\Enums\PotentialCustomerStatus;
use App\Enums\FollowUpReason;
use App\Enums\RejectionReason;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerFollowUp>
 */
class CustomerFollowUpFactory extends Factory
{
    protected $model = CustomerFollowUp::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 1. تحديد الحالة (Status) أولاً بشكل عشوائي
        $status = $this->faker->randomElement(PotentialCustomerStatus::cases());

        // 2. بناءً على الحالة، يتم اختيار الـ Enum المناسب للسبب (Reason) لضمان التوافق
        if ($status === PotentialCustomerStatus::CANCELLED) {
            $reason = $this->faker->randomElement(RejectionReason::cases())->value;
        } else {
            $reason = $this->faker->randomElement(FollowUpReason::cases())->value;
        }

        return [
            // ربط المتابعة بعميل محتمل موجود بالفعل في النظام تلقائياً أو إنشاء واحد جديد
            'potential_customer_id' => PotentialCustomer::inRandomOrder()->first()?->id ?? PotentialCustomer::factory(),
            
            // تخصيص المتابعات للمستخدمين ذوي المعرفات 1، 2، 3
            'user_id' => $this->faker->randomElement([1, 2, 3]),
            
            // تمرير الحالة كـ Enum object (لأن الموديل يقوم بعمل Casting لها)
            'status' => $status,
            
            // تمرير قيمة السبب المتوافقة كـ string
            'reason' => $reason,
            
            // وضع تاريخ عشوائي للمتابعة القادمة خلال الأيام الـ 14 القادمة
            'next_follow_up_at' => $this->faker->dateTimeBetween('now', '+14 days'),
            
            // توليد نصوص عشوائية قصيرة للملاحظات (80% تحتوي على ملاحظات و 20% فارغة)
            'notes' => $this->faker->optional(0.8)->sentence(),
        ];
    }
}