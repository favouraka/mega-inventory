<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\ProductImage;

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
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(10),
            'category_id' => Category::factory()->create(),
            'weight' => 100,
            'height' => 1,
            'length' => 2,
            'width' => 2.25,
            'upc_code' => $this->faker->ean8(),
            'sku_code' => $this->faker->ean13(),
            'price_ngn' => $this->faker->randomNumber(5, true),
            'price_cfa' => $this->faker->randomNumber(5, true),
        ];
    }
}
