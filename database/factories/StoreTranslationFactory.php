<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StoreTranslation>
 */
class StoreTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id' => Product::factory(), // Use Store factory to generate associated product
            'language' => 'en', // Default language; overridden during specific creation
            'name' => fake()->words(3, true), // Random store name
        ];
    }
}
