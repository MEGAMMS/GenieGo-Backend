<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Site>
 */
class SiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory()->withTranslations(),
            'customer_id' => null, // Default to no customer unless recycled
            'name' => fake()->name(),
            'address' => fake()->address(),
        ];
    }

    /**
     * Recycle customers.
     *
     * @param  \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|array<int, \App\Models\Customer>  $customers
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function recycle($customers)
    {
        return $this->state(function () use ($customers) {
            return [
                'customer_id' => is_array($customers) || $customers instanceof \Illuminate\Support\Collection
                    ? fake()->randomElement($customers)->id
                    : $customers->id,
                'store_id' => null, // Nullify the store_id when customers are recycled
            ];
        });
    }
}
