<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Store;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
public function it_can_search_products_and_stores_with_translations_and_tags()
{
    // 📝 Arrange: Seed data

    // Create a product with explicit translations
    $product = Product::factory()
        ->withTranslations([
            [
                'language' => 'en',
                'name' => 'Laptop',
                'description' => 'A high-end laptop.',
            ],
            [
                'language' => 'fr',
                'name' => 'Ordinateur Portable',
                'description' => 'Un ordinateur portable haut de gamme.',
            ],
        ])
        ->create();

    // Create a store with explicit translations
    $store = Store::factory()
        ->withTranslations([
            [
                'language' => 'en',
                'name' => 'Tech Store',
            ],
            [
                'language' => 'ar',
                'name' => 'متجر تقني',
            ],
        ])
        ->create();

    // Create a tag and attach it
    $tag = Tag::factory()->create(['name' => 'electronics']);

    $product->tags()->attach($tag);
    $store->tags()->attach($tag);

    // 🛒 Act: Perform search request
    $response = $this->postJson('/api/search?query=Tech&tags[]=electronics');

    // ✅ Assert: Validate JSON structure and content
    $response->assertStatus(200)
             ->assertJson([
                 'products' => [
                     [
                         'id' => $product->id,
                         'name' => 'Laptop',
                         'description' => 'A high-end laptop.',
                         'tags' => ['electronics'],
                     ]
                 ],
                 'stores' => [
                     [
                         'id' => $store->id,
                         'name' => 'Tech Store',
                         'tags' => ['electronics'],
                         'location' => null,
                     ]
                 ]
             ]);
}

}


