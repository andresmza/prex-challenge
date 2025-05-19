<?php

namespace App\DTOs\Auth;

use App\Models\User;
use JsonSerializable;

/**
 * Data Transfer Object for login response.
 */
class LoginResponseDTO implements JsonSerializable
{
    /**
     * Create a new LoginResponseDTO instance.
     *
     * @param string $accessToken The access token
     * @param string $tokenType The token type
     * @param int $expiresIn Token expiration time in seconds
     * @param User $user The authenticated user
     */
    public function __construct(
        public readonly string $accessToken,
        public readonly string $tokenType,
        public readonly int $expiresIn,
        public readonly User $user
    ) {
    }

    /**
     * Get user data with only necessary fields.
     *
     * @return array<string, mixed>
     */
    public function getUserData(): array
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
        ];
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'access_token' => $this->accessToken,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
            'user' => $this->getUserData(),
        ];
    }
}
