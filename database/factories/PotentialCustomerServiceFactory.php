<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\PotentialCustomer;
use App\Models\PotentialCustomerService;
use App\Enums\CompanyService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PotentialCustomerService>
 */
class PotentialCustomerServiceFactory extends Factory
{
    protected $model = PotentialCustomerService::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // ربط السجل بعميل محتمل موجود عشوائياً، وإذا لم يوجد يتم إنشاء عميل جديد عبر الفاكتوري الخاص به
            'potential_customer_id' => PotentialCustomer::inRandomOrder()->first()?->id ?? PotentialCustomer::factory(),
            
            // تخصيص السجلات للمستخدمين (الموظفين) ذوي المعرفات 1، 2، 3 لضمان التوافق مع السييدرز السابقة
            'user_id' => $this->faker->randomElement([1, 2, 3]),
            
            // سحب قيمة عشوائية مباشرة من الـ Enum (لأن الموديل يقوم بعمل كاستنج لها تلقائياً)
            'service_type' => $this->faker->randomElement(CompanyService::cases()),
            
            // توليد ملاحظات نصية عشوائية (85% تحتوي على نصوص و 15% تكون فارغة null)
            'notes' => $this->faker->optional(0.85)->sentence(8),
            
            // توليد تاريخ عشوائي للسجل خلال الـ 30 يوماً الماضية
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => function (array $attributes) {
                return $attributes['created_at'];
            },
        ];
    }
}