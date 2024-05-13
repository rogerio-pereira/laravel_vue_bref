<?php

namespace Tests\Feature\App\Controller\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_route_user_should_return_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get('/api/user')
            ->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'picture' => null,
            ]);
    }
    
    public function test_route_user_should_redirect_when_guest()
    {
        $user = User::factory()->create();

        $this->get('/api/user')
            ->assertStatus(302);
    }
}