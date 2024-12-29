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
            'customer_id' => Customer::factory(),
            'store_id' => Store::factory()->withTranslations(),
            'total_price' => fake()->randomFloat(2, 1, 100),
            'status' => fake()->randomElement(['delivered', 'canceled', 'pending']),
        ];
    }

    public function withProducts($products)
    {
        return $this->afterCreating(function ($order) use ($products) {
            $totalAmount = 0;

            foreach ($products as $product) {
                $quantity = rand(1, 10); // Random quantity for each product
                $totalAmount += $product->price * $quantity;

                // Attach the product to the order with pivot data
                $order->products()->attach($product->id, [
                    'quantity' => $quantity,
                ]);
            }

            // Update the total amount for the order
            $order->update(['total_price' => $totalAmount]);
        });
    }
}
