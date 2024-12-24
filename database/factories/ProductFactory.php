<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

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
        $fakeImagesPath = 'fake-images/products/';
        // Get all icons from the 'public/fake-images/brands' directory
        $iconDirectory = storage_path('app/public/'.$fakeImagesPath);
        $icons = File::exists($iconDirectory)
            ? File::files($iconDirectory)
            : [];

        // Map to relative paths for storage
        $iconPaths = array_map(function ($file) use ($fakeImagesPath) {
            return $fakeImagesPath.$file->getFilename();
        }, $icons);

        return [
            'store_id' => Store::factory(),
            'icon' => $this->faker->randomElement($iconPaths),
            'price' => fake()->randomFloat(2, 1, 100), // Generate a price between 1.00 and 100.00
        ];
    }

    /**
     * Configure the factory.
     */
    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            // Create translations for both 'en' and 'ar' languages
            ProductTranslation::factory()->create([
                'product_id' => $product->id,
                'language' => 'en',
            ]);

            ProductTranslation::factory()->create([
                'product_id' => $product->id,
                'name' => 'اسم منتج عشوائي',
                'description' => 'هذا هو الوصف العشوائي للمنتج.',
                'language' => 'ar',
            ]);
        });
    }
}
