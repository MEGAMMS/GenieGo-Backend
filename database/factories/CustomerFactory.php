<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
        ];
    }

    /**
     * Attach sites to the customer.
     *
     * @param  array|Collection  $sites
     */
    public function withSites($sites): static
    {
        return $this->afterCreating(function ($customer) use ($sites) {
            $customer->sites()->attach($sites);
        });
    }
}
