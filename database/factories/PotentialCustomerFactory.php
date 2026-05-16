<?php

namespace Database\Factories;

use App\Models\PotentialCustomer;
use App\Enums\PotentialCustomerStatus; // أضفنا الـ Enum هنا
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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

            // سحب القيم ديناميكيًا من الـ Enum مباشرة
            'status' => $this->faker->randomElement(
                array_column(PotentialCustomerStatus::cases(), 'value')
            ),

            'source' => $this->faker->randomElement(
                array_column(PotentialCustomerSource::cases(), 'value')
            ),
            'added_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'added_by' => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}