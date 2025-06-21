<?php

namespace Database\Factories;

use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $basePrice = $this->faker->randomFloat(2, 100, 500); // base_price 100–500

        // 50% sản phẩm có giảm giá, 50% không
        $salePrice = fake()->boolean(50)
            ? fake()->randomFloat(2, $basePrice * 0.5, $basePrice * 0.9)
            : null;

        return [
            'name' => $this->faker->words(2, true),
            'product_type_id' => \App\Models\ProductType::inRandomOrder()->first()->id,
            'description' => $this->faker->sentence(),
            'info' => json_encode([
                'lượng' => rand(10, 60) . 'ml',
                'nặng' => rand(30, 90) . 'g'
            ]),
            'base_price' => $basePrice,
            'sale_price' => $salePrice,
            'quantity' => rand(0, 100),
        ];
    }
}
