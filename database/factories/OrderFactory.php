<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal']),
            'customer_phone' => $this->faker->phoneNumber,
            'customer_name' => $this->faker->name,
            'customer_email' => $this->faker->email,
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            'reference' => 'ORD-' . fake()->asciify('****************'),
        ];
    }
}
