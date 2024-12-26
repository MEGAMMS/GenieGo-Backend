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
    public function it_returns_correct_search_results()
    {
        // 📝 Arrange: Seed data
        
        // Create a product with explicit translations

        $Ptrans=ProductTranslation::factory()->create([
            'language' => 'en',
            'name' => 'Laptop',
            'description' => 'A high-end laptop.',
        ]);
        
        $product = Product::factory()->withTranslations([$Ptrans])->create();

        // Create a store with explicit translations
        $store = Store::factory()->create();

        ProductTranslation::factory()->english()->create([
            'product_id' => $store->id,
            'name' => 'Tech Store',
        ]);

        ProductTranslation::factory()->arabic()->create([
            'product_id' => $store->id,
        ]);

        // Create a tag and attach it
        $tag = Tag::factory()->create(['name' => 'electronics']);

        $product->tags()->attach($tag);
        $store->tags()->attach($tag);

        // 🛒 Act: Perform search request
        $response = $this->getJson('/api/search?query=Tech&tags[]=electronics');

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


