<?php

namespace App\Services\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Models\User;

/**
 * Interface for user authentication.
 */
interface UserAuthenticatorInterface
{
    /**
     * Authenticate a user with credentials.
     *
     * @param LoginDTO $credentials User login credentials
     * @return User|null Authenticated user or null if authentication fails
     */
    public function authenticate(LoginDTO $credentials): ?User;
}
