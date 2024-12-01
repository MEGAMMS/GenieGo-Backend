<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Store;
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
            'customer_id'=>Customer::factory(),
            'store_id'=>Store::factory(),
            'total_price'=>fake()->randomFloat(2, 1, 100),
            'status'=>fake()->randomElement(['delivered','canceled','pending']),
        ];
    }
}
