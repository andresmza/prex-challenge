<?php

namespace App\Services\Auth;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\LoginResponseDTO;

/**
 * Interface for authentication service.
 */
interface AuthServiceInterface
{
    /**
     * Authenticate a user with credentials and return an access token.
     *
     * @param LoginDTO $credentials User login credentials
     * @return LoginResponseDTO|null Login response or null if authentication fails
     */
    public function login(LoginDTO $credentials): ?LoginResponseDTO;
}
