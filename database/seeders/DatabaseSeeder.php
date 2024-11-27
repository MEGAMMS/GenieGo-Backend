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

        $user=User::factory()->create();
        $user2=User::factory()->create();
        $role= Role::factory()->create();

        //$owner=Owner::factory()->create(['user_id'=>$user->id]);
        $customer=Customer::factory()->create(['user_id'=>$user2->id]);


        
        
    }
}
