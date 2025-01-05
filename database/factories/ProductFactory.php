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
        $iconDirectory = storage_path('app/public/'.$fakeImagesPath);
        $icons = File::exists($iconDirectory) ? File::files($iconDirectory) : [];

        $iconPaths = array_map(function ($file) use ($fakeImagesPath) {
            return $fakeImagesPath.$file->getFilename();
        }, $icons);

        return [
            'store_id' => Store::factory()->withTranslations(),
            'icon' => $this->faker->randomElement($iconPaths),
            'price' => $this->faker->randomFloat(2, 1, 100), // Generate a price between 1.00 and 100.00
        ];
    }

    /**
     * State for adding custom translations.
     *
     * @return static
     */
    public function withTranslations(array $translations = [])
    {
        return $this->afterCreating(function (Product $product) use ($translations) {
            // Default translations (if no custom translations are provided)
            $defaultTranslations = [
                [
                    'language' => 'en',
                    'name' => $this->faker->word,
                    'description' => $this->faker->sentence,
                ],
                [
                    'language' => 'ar',
                    'name' => 'اسم منتج عشوائي',
                    'description' => 'هذا هو الوصف العشوائي للمنتج.',
                ],
            ];

            // Merge default translations with custom ones, if provided
            $translations = $translations ? $translations : $defaultTranslations;

            // Create translations
            foreach ($translations as $translation) {
                ProductTranslation::factory()->create(array_merge($translation, [
                    'product_id' => $product->id,
                ]));
            }
        });
    }

    public function withTags($tags)
    {
        return $this->afterCreating(function (Product $product) use ($tags) {
            foreach ($tags as $tag) {
                // Attach the tag to the order with pivot data
                $product->tags()->attach($tag->id);
            }
        });
    }
}
