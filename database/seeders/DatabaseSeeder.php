<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Owner;
use App\Models\Store;
use App\Models\Product;
use App\Models\Customer;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductTranslation;

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
            'password' => 'P@ssw0rd'
        ]);

        $users = User::factory(10)->create();

        //Customer::factory(10)->recycle($users)->create();
        $tags = Tag::factory(100)->create();
        $customer = Customer::factory()->recycle($user)->create();
        $store = Store::factory()->withTags($tags)->create();
        $products = Product::factory(10)->recycle($store)->create();

        $owner = Owner::factory()->recycle($store)->recycle($user)->create();

        $order = Order::factory()->recycle($customer)->withProducts($products)->create();



        Customer::factory(10)->recycle($users)->create();
        $products = Product::factory(10)->create();
        Store::factory()->count(10)->create();
    }
}
