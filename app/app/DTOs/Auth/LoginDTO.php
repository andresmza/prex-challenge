<?php

namespace App\DTOs\Auth;

/**
 * Data Transfer Object for login credentials.
 */
class LoginDTO
{
    /**
     * Create a new LoginDTO instance.
     *
     * @param string $email User email
     * @param string $password User password
     */
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {
    }

    /**
     * Create a LoginDTO from request data.
     *
     * @param array $data Request data containing email and password
     * @return self
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password']
        );
    }
}
