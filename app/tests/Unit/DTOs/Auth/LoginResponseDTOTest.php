<?php

namespace Tests\Unit\DTOs\Auth;

use App\DTOs\Auth\LoginResponseDTO;
use App\Models\User;
use Tests\TestCase;

class LoginResponseDTOTest extends TestCase
{
    /**
     * Test that the DTO can be created with valid data.
     */
    public function test_can_create_with_valid_data(): void
    {
        $user = new User([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'hashed_password',
        ]);

        $dto = new LoginResponseDTO(
            accessToken: 'test-token',
            tokenType: 'Bearer',
            expiresIn: 3600,
            user: $user
        );

        $this->assertEquals('test-token', $dto->accessToken);
        $this->assertEquals('Bearer', $dto->tokenType);
        $this->assertEquals(3600, $dto->expiresIn);
        $this->assertSame($user, $dto->user);
    }

    /**
     * Test that getUserData returns only non-sensitive user data.
     */
    public function test_get_user_data_returns_only_non_sensitive_data(): void
    {
        $user = new User([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'hashed_password',
            'remember_token' => 'some_token',
        ]);

        $dto = new LoginResponseDTO(
            accessToken: 'test-token',
            tokenType: 'Bearer',
            expiresIn: 3600,
            user: $user
        );

        $userData = $dto->getUserData();

        $this->assertArrayHasKey('id', $userData);
        $this->assertArrayHasKey('name', $userData);
        $this->assertArrayHasKey('email', $userData);

        $this->assertArrayNotHasKey('password', $userData);
        $this->assertArrayNotHasKey('remember_token', $userData);
    }

    /**
     * Test that jsonSerialize returns the correct structure.
     */
    public function test_json_serialize_returns_correct_structure(): void
    {
        $user = new User([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $dto = new LoginResponseDTO(
            accessToken: 'test-token',
            tokenType: 'Bearer',
            expiresIn: 3600,
            user: $user
        );

        $json = $dto->jsonSerialize();

        $this->assertArrayHasKey('access_token', $json);
        $this->assertArrayHasKey('token_type', $json);
        $this->assertArrayHasKey('expires_in', $json);
        $this->assertArrayHasKey('user', $json);

        $this->assertArrayHasKey('id', $json['user']);
        $this->assertArrayHasKey('name', $json['user']);
        $this->assertArrayHasKey('email', $json['user']);

        $this->assertArrayNotHasKey('password', $json['user']);
        $this->assertArrayNotHasKey('remember_token', $json['user']);
    }
}
