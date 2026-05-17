<?php

namespace Database\Factories;

use App\Models\CustomerFollowUp;
use App\Models\PotentialCustomer;
use App\Enums\PotentialCustomerStatus;
use App\Enums\PotentialCustomerReason;
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
        return [
            // ربط المتابعة بعميل محتمل موجود بالفعل في النظام تلقائياً
            'potential_customer_id' => PotentialCustomer::inRandomOrder()->first()?->id ?? PotentialCustomer::factory(),
            
            // تخصيص المتابعات فقط للمستخدمين ذوي المعرفات 1، 2، 3 بناءً على طلبك
            'user_id' => $this->faker->randomElement([1, 2, 3]),
            
            // سحب الحالات ديناميكياً من الـ Enum الخاص بحالات العميل المحتمل
            'status' => $this->faker->randomElement(
                array_column(PotentialCustomerStatus::cases(), 'value')
            ),
            
            // سحب الأسباب ديناميكياً من الـ Enum الجديد الذي أرفقته لضمان التطابق التام
            'reason' => $this->faker->randomElement(
                array_column(PotentialCustomerReason::cases(), 'value')
            ),
            
            // وضع تاريخ عشوائي للمتابعة القادمة خلال الأيام الـ 14 القادمة
            'next_follow_up_at' => $this->faker->dateTimeBetween('now', '+14 days'),
            
            // توليد نصوص عشوائية قصيرة للملاحظات
            'notes' => $this->faker->optional(0.8)->sentence(), // 80% تحتوي على ملاحظات و 20% فارغة
        ];
    }
}