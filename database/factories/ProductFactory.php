<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\Product;
use App\Models\ProductTranslation;
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
        return [
            'store_id' => Store::factory(),
            'price' => fake()->randomFloat(2, 1, 100), // Generate a price between 1.00 and 100.00
        ];
    }
    /**
     * Configure the factory.
     */
    public function configure():static
    {
        return $this->afterCreating(function (Product $product) {
            // Create translations for both 'en' and 'ar' languages
            ProductTranslation::factory()->create([
                'product_id' => $product->id,
                'language' => 'en',
            ]);

            ProductTranslation::factory()->create([
                'product_id' => $product->id,
                'name' => "اسم منتج عشوائي",
                'description' => "هذا هو الوصف العشوائي للمنتج.",
                'language' => 'ar',
            ]);
        });
    }
}
