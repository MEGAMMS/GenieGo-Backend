<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Store;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function PHPUnit\Framework\assertJson;

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
    $response = $this->postJson('/api/search',
        ['query'=>'tech store',
          'tags'=>[
            'electronics'
          ]]);

    // ✅ Assert: Validate JSON structure and content
    $response->assertStatus(200) 
        ->assertJsonCount(1, 'data.stores');  // Assert there is exactly 1 store
}

    /** @test */
    public function it_can_search_portions_of_a_word()
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
                    'name' => 'top Tech Store',
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
        $response = $this->postJson('/api/search',
            ['query'=>'top',
              'tags'=>[
                'electronics'
              ]]);
    
        // ✅ Assert: Validate JSON structure and content
        $response->assertStatus(200) 
            ->assertJsonCount(1, 'data.stores')
            ->assertJsonCount(1,'data.products');  
    }

        /** @test */
        public function it_can_search_all_tags_and()
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
                        'name' => 'top Tech Store',
                    ],
                    [
                        'language' => 'ar',
                        'name' => 'متجر تقني',
                    ],
                ])
                ->create();
        
            // Create a tag and attach it
            $tag = Tag::factory()->create(['name' => 'electronics']);
            $tag2 = Tag::factory()->create(['name' => 'food']);
        
            $product->tags()->attach($tag);
            $store->tags()->attach($tag);
            $store->tags()->attach($tag2);
        
            // 🛒 Act: Perform search request
            $response = $this->postJson('/api/search',
                ['query'=>'top',
                  'tags'=>[
                    'electronics','food'
                  ]]);
        
            // ✅ Assert: Validate JSON structure and content
            $response->assertStatus(200) 
                ->assertJsonCount(1, 'data.stores');
        }

               /** @test */
        public function it_can_search_without_tags()
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
                        'name' => 'top Tech Store',
                    ],
                    [
                        'language' => 'ar',
                        'name' => 'متجر تقني',
                    ],
                ])
                ->create();
        
            // Create a tag and attach it
            $tag = Tag::factory()->create(['name' => 'electronics']);
            $tag2 = Tag::factory()->create(['name' => 'food']);
        
            $product->tags()->attach($tag);
            $store->tags()->attach($tag);
            $store->tags()->attach($tag2);
        
            // 🛒 Act: Perform search request
            $response = $this->postJson('/api/search',
                ['query'=>'top',
                  'tags'=>[
                  ]]);
        
            // ✅ Assert: Validate JSON structure and content
            $response->assertStatus(200) 
                ->assertJsonCount(1, 'data.stores')
                ->assertJsonCount(1, 'data.products');
        }

               /** @test */
        public function it_can_search_with_just_tags_()
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
                        'name' => 'top Tech Store',
                    ],
                    [
                        'language' => 'ar',
                        'name' => 'متجر تقني',
                    ],
                ])
                ->create();
        
            // Create a tag and attach it
            $tag = Tag::factory()->create(['name' => 'electronics']);
            $tag2 = Tag::factory()->create(['name' => 'food']);
        
            $product->tags()->attach($tag);
            $store->tags()->attach($tag);
            $store->tags()->attach($tag2);
        
            // 🛒 Act: Perform search request
            $response = $this->postJson('/api/search',
                ['query'=>'',
                  'tags'=>[
                    'electronics','food'
                  ]]);
        
            // ✅ Assert: Validate JSON structure and content
            $response->assertStatus(200) 
                ->assertJsonCount(1, 'data.stores');
        }

}


