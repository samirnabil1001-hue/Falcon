<?php

namespace Database\Factories;

use App\Models\PotentialCustomer;
use App\Enums\PotentialCustomerStatus; 
use App\Enums\PotentialCustomerSource; // 👈 هذا هو السطر الناقص الذي تسبب في الخطأ
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PotentialCustomer>
 */
class PotentialCustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),

            // سحب القيم ديناميكيًا من الـ Enum الخاص بالحالة
            'status' => $this->faker->randomElement(
                array_column(PotentialCustomerStatus::cases(), 'value')
            ),

            // سحب القيم ديناميكيًا من الـ Enum الخاص بالمصدر
            'source' => $this->faker->randomElement(
                array_column(PotentialCustomerSource::cases(), 'value')
            ),
            
            'added_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'added_by' => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}