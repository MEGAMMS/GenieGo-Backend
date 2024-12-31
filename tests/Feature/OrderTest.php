<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function authenticated_user_can_access_orders()
    {
        // Create a user
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        // Make a request to the protected route
        $response = $this->get('/api/orders');

        // Assert the response
        $response->assertStatus(200);
    }
}
