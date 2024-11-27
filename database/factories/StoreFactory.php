<?php

namespace Database\Factories;

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
        return [
            'site_id'=>Site::factory();
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
