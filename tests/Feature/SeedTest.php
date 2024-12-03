<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Owner;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SeedTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_if_factories_are_storing_data(): void
    {
        $user=User::factory()->create([
            'username' => 'test',
            'email' => 'test@example.com',
            'phone' => '+963987654321',
            'password' => 'P@ssw0rd'
        ]);
        $customer=Customer::factory()->recycle($user)->create();
        $tore=Store::factory()->create();
        $products = Product::factory(10)->recycle($tore)->create();

        $owner=Owner::factory()->recycle($tore)->recycle($user)->create();

        $order=Order::factory()->recycle($customer)->withProducts($products)->create();

        $this->assertDatabaseHas('users',['id'=>$user->id]);
        $this->assertDatabaseHas('customers',['id'=>$customer->id,'user_id'=>$user->id]);
        $this->assertDatabaseHas('owners',['id'=>$owner->id,'user_id'=>$user->id,'store_id'=>$tore->id]);
        // $this->assertDatabaseHas('users',['id'=>$user->id]);
        $this->assertCount(10, $tore->products);
        $this->assertCount(1, $customer->orders);
        $this->assertCount(10, $order->products);
        $this->assertTrue($tore->owner->is($owner));

    }
}
