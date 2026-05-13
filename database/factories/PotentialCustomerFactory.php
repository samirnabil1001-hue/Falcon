<?php

namespace Database\Factories;

use App\Models\PotentialCustomer;
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
            'name'     => $this->faker->name(),
            'phone'    => $this->faker->phoneNumber(),
            'status'   => $this->faker->randomElement(['new', 'contacted', 'converted', 'lost']),
            'source'   => $this->faker->randomElement(['Facebook', 'Google Ads', 'Instagram', 'Referral', 'Website']),
            'added_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'added_by' => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}