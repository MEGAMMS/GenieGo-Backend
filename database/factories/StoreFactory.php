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
        $iconDirectory = public_path('fake-icons');
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
}
