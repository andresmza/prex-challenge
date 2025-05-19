<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        /** @var Authenticatable $authUser */
        $authUser = $user;

        $this->actingAs($authUser, 'api');

        $this->assertTrue(auth('api')->check());
        $this->assertEquals($user->id, auth('api')->id());
    }

    /**
     * Test unauthenticated access is denied.
     */
    public function test_unauthenticated_access_is_denied(): void
    {
        $this->assertFalse(auth('api')->check());
        $this->assertNull(auth('api')->user());
    }
}
