<?php

namespace Database\Factories;

use App\Models\Site;

use App\Models\Store;
use App\Models\StoreTranslation;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        // Get all icons from the 'public/fake-icons' directory
        $iconDirectory = storage_path('app/public/fake-icons');
        $icons = File::exists($iconDirectory) 
            ? File::files($iconDirectory) 
            : [];

        // Map to relative paths for storage
        $iconPaths = array_map(function ($file) {
            return 'fake-icons/' . $file->getFilename();
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
                'name' => "اسم متجر عشوائي",
                'language' => 'ar',
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
     * Customize with specific translations.
     */
    public function withTranslations(array $translations): static
    {
        return $this->afterCreating(function (Store $store) use ($translations) {
            foreach ($translations as $translation) {
                StoreTranslation::factory()->create([
                    'product_id' => $store->id,
                    'language' => $translation['language'],
                    'name' => $translation['name'] ?? fake()->words(3, true),
                    'description' => $translation['description'] ?? fake()->paragraph(),
                ]);
            }
        });
    }
}
