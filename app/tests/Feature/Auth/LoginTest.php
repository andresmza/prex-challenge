<?php

namespace Tests\Feature\Auth;

use App\DTOs\Auth\LoginResponseDTO;
use App\Models\User;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Auth\FakeTokenIssuer;
use App\Services\Auth\FakeUserAuthenticator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can login with valid credentials.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->app->bind(AuthServiceInterface::class, function ($app) use ($user) {
            $authService = Mockery::mock(AuthServiceInterface::class);

            $authService->shouldReceive('login')
                ->andReturn(new LoginResponseDTO(
                    accessToken: 'test-token',
                    tokenType: 'Bearer',
                    expiresIn: 3600,
                    user: $user
                ));

            return $authService;
        });

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                     'expires_in',
                     'user' => [
                         'id',
                         'name',
                         'email',
                     ],
                 ]);
    }

    /**
     * Test that a user cannot login with invalid credentials.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->app->bind(AuthServiceInterface::class, function ($app) {
            $authService = Mockery::mock(AuthServiceInterface::class);

            $authService->shouldReceive('login')
                ->andReturn(null);

            return $authService;
        });

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
                 ->assertJson([
                     'message' => 'Invalid credentials',
                 ]);
    }

    /**
     * Test the direct integration of our authentication components.
     */
    public function test_direct_integration_of_auth_components(): void
    {
        $existingUser = User::query()->where('email', 'test@example.com')->first();

        if (! $existingUser) {
            User::factory()->create([
                'email' => 'test@example.com',
                'password' => bcrypt('password123'),
            ]);
        }

        $userAuthenticator = new FakeUserAuthenticator();
        $tokenIssuer = new FakeTokenIssuer();

        $credentials = new \App\DTOs\Auth\LoginDTO(
            email: 'test@example.com',
            password: 'password123'
        );

        $user = $userAuthenticator->authenticate($credentials);
        $tokenInfo = $tokenIssuer->issueToken($user);

        $this->assertNotNull($user);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertArrayHasKey('accessToken', $tokenInfo);
        $this->assertArrayHasKey('expiresIn', $tokenInfo);
        $this->assertStringContainsString('test-token', $tokenInfo['accessToken']);
    }
}
