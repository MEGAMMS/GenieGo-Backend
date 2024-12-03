<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\User;
use App\Models\Role;
use App\Models\Owner;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Store;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $user=User::factory()->create([
            'username' => 'test',
            'email' => 'test@example.com',
            'phone' => '+963987654321',
            'password' => 'P@ssw0rd'
        ]);

        $users = User::factory(10)->create();

        //Customer::factory(10)->recycle($users)->create();
        $customer=Customer::factory()->recycle($user)->create();
        $tore=Store::factory()->create();
        $products = Product::factory(10)->recycle($tore)->create();

        $owner=Owner::factory()->recycle($tore)->recycle($user)->create();

        $order=Order::factory()->recycle($customer)->withProducts($products)->create();


        
        Customer::factory(10)->recycle($users)->create();
        $products = Product::factory(10)->create();
        Store::factory()->count(10)->create();
    }
}
