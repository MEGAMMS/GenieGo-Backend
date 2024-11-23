<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductTranslation>
 */
class ProductTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(), // Use Product factory to generate associated product
            'language' => 'en', // Default language; overridden during specific creation
            'name' => fake()->words(3, true), // Random product name
            'description' => fake()->paragraph(), // Random description
        ];
    }
}
