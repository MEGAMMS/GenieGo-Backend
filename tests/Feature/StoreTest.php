<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_store_with_icon_and_translations()
    {
        // Fake the storage disk
        Storage::fake('public');

        // Prepare test data
        $icon = UploadedFile::fake()->image('store-icon.jpg');
        $payload = [
            'icon' => $icon,
            'translations' => [
                [
                    'language' => 'en',
                    'name' => 'Test Store',
                    'description' => 'This is a test store description'
                ],
                [
                    'language' => 'fr',
                    'name' => 'Magasin Test',
                    'description' => 'Ceci est une description de test'
                ],
            ],
        ];

        // Make the API request
        $response = $this->postJson('/api/stores', $payload);

        // Assertions
        $response->assertStatus(201);
        $this->assertDatabaseHas('stores', [
            'icon' => 'stores/icons/' . $icon->hashName(),
        ]);

        // Check if file exists in storage
        $this->assertTrue(Storage::disk('public')->exists('stores/icons/' . $icon->hashName()));

        // Assert translations
        $this->assertDatabaseHas('store_translations', [
            'language' => 'en',
            'name' => 'Test Store',
        ]);
        $this->assertDatabaseHas('store_translations', [
            'language' => 'fr',
            'name' => 'Magasin Test',
        ]);
    }
}
