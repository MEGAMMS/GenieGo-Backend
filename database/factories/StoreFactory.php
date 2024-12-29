<?php

namespace Database\Factories;

use App\Models\Site;
use App\Models\Store;
use App\Models\StoreTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakeImagesPath = 'fake-images/brands/';
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
            'site_id' => Site::factory(),
            'icon' => $this->faker->randomElement($iconPaths),
        ];
    }

    /**
     * Configure the factory.
     */
    public function configure()
    {
        return $this->afterCreating(function (Store $store) {
            // Create translations for both 'en' and 'ar' languages
            StoreTranslation::factory()->create([
                'store_id' => $store->id,
                'language' => 'en',
            ]);

            StoreTranslation::factory()->create([
                'store_id' => $store->id,
                'language' => 'ar',
                'name' => 'اسم متجر عشوائي',
                'description' => 'هذا هو الوصف العشوائي للمنتج.',
            ]);
        });
    }

    public function withTags($tags)
    {
        return $this->afterCreating(function (Store $store) use ($tags) {
            foreach ($tags as $tag) {
                // Attach the tag to the order with pivot data
                $store->tags()->attach($tag->id);
            }
        });
    }

        /**
     * State for adding custom translations.
     *
     * @return static
     */
    public function withTranslations(array $translations = [])
    {
        return $this->afterCreating(function (Store $store) use ($translations) {
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
                StoreTranslation::factory()->create(array_merge($translation, [
                    'store_id' => $store->id,
                ]));
            }
        });
    }
}
