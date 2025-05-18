<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PassportMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Authenticate the user using the Passport guard for testing purposes.
     */
    public function test_user_can_be_authenticated_with_passport(): void
    {
        // Create a user for authentication test
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        // PHPStan: ensure $authUser is recognized as Authenticatable
        /** @var Authenticatable $authUser */
        $authUser = $user;

        // Authenticate user using Passport guard
        $this->actingAs($authUser, 'api');

        // Request protected endpoint
        $response = $this->getJson('/api/me');

        // Assert response contains the authenticated user data
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Test unauthenticated access is denied.
     */
    public function test_unauthenticated_access_is_denied(): void
    {
        // Make a request to a protected endpoint without authentication
        $response = $this->getJson('/api/me');

        // Assert the response indicates authentication is required
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
