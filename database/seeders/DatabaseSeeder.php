<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Owner;
use App\Models\Product;
use App\Models\Site;
use App\Models\Store;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $testUser = User::factory()->create([
            'username' => 'test',
            'email' => 'test@example.com',
            'phone' => '+963987654321',
            'password' => 'P@ssw0rd',
        ]);
        /* $siteForNoOne = Site::factory()->create(); */
        $testCustomer = Customer::factory()->recycle($testUser)->create();
        Site::factory(10)->recycle($testCustomer)->create();
        Site::factory(10)->create();

        Product::factory()
            ->withTranslations([
                [
                    'language' => 'en',
                    'name' => 'Custom English Name',
                    'description' => 'Custom English Description',
                ],
                [
                    'language' => 'ar',
                    'name' => 'اثممخخخخخخخخخخ',
                    'description' => 'ثامخخخمنشيسبت',
                ],
            ])
            ->create();
        $users = User::factory(10)->create();

        Customer::factory(10)->recycle($users)->create();
        $tags = Tag::factory(4)->create();
        $stores = Store::factory(10)->withTranslations()->withTags($tags)->create();
        $products = Product::factory(30)->withTranslations()->recycle($stores)->create();

        Order::factory()->recycle($testCustomer)->withProducts($products)->create();
        Owner::factory()->recycle($stores)->recycle($testUser)->create();

        $products = Product::factory(10)->withTranslations()->create();

        $product = Product::factory()
            ->withTranslations([
                [
                    'language' => 'en',
                    'name' => 'Laptop',
                    'description' => 'A high-end laptop.',
                ],
                [
                    'language' => 'ar',
                    'name' => 'اثمثمثخثخثتنمشيتنمششيسبتنمكبشيستنمكشسشبتنمكشيسبتمكشيسبتنمكشسيبتنمكشيسبتنمكشيسبتنمك',
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
        Store::factory()
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
        $electornicsTag = Tag::factory()->create(['name' => 'electronics']);
        $helloTag = Tag::factory()->create(['name' => 'hello']);

        $product->tags()->attach($electornicsTag);
        $store->tags()->attach($electornicsTag);
        $store->tags()->attach($helloTag);
    }
}
