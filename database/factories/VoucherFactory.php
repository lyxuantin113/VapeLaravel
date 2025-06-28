<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $type = $this->faker->randomElement(['fixed', 'percent']);
        return [
            'code' => strtoupper(Str::random(6)),
            'discount_type' => $type,
            'discount_value' => $type === 'fixed'
                ? $this->faker->numberBetween(10000, 50000)
                : $this->faker->numberBetween(5, 50), // percent
            'start_date' => now()->subDays(rand(0, 5)),
            'end_date' => now()->addDays(rand(5, 10)),
        ];
    }
}
