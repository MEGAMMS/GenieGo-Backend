<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Owner;
use App\Models\Product;
use App\Models\Store;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $user = User::factory()->create([
            'username' => 'test',
            'email' => 'test@example.com',
            'phone' => '+963987654321',
            'password' => 'P@ssw0rd',
        ]);
        Product::factory()
            ->withTranslations([
                [
                    'language' => 'en',
                    'name' => 'Custom English Name',
                    'description' => 'Custom English Description',
                ],
                [
                    'language' => 'fr',
                    'name' => 'Nom personnalisé en français',
                    'description' => 'Description personnalisée en français',
                ],
            ])
            ->create();
        $users = User::factory(10)->create();

        //Customer::factory(10)->recycle($users)->create();
        $tags = Tag::factory(1)->create();
        $customer = Customer::factory()->recycle($user)->create();
        $store = Store::factory()->withTags($tags)->create();
        $products = Product::factory(30)->withTranslations()->recycle($store)->create();

        $owner = Owner::factory()->recycle($store)->recycle($user)->create();

        $order = Order::factory()->recycle($customer)->withProducts($products)->create();

        Customer::factory(10)->recycle($users)->create();
        $products = Product::factory(10)->withTranslations()->create();
        Store::factory()->count(10)->create();
    }
}
