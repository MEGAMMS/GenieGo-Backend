<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\User;
use App\Models\Role;
use App\Models\Owner;
use App\Models\Customer;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        User::factory()->create([
            'username' => 'test',
            'email' => 'test@example.com',
            'phone' => '0987654321',
            'password' => 'password'
        ]);

        $users = User::factory(10)->create();

        Customer::factory(10)->recycle($users)->create();
        $products = Product::factory(10)->create();
    }
}
