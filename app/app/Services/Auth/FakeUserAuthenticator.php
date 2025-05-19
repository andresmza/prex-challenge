<?php

namespace App\Services\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Models\User;

/**
 * Fake implementation of user authenticator for testing.
 */
class FakeUserAuthenticator implements UserAuthenticatorInterface
{
    /**
     * Authenticate a user with credentials.
     * In testing environment, always authenticates if email is test@example.com and password is password123.
     *
     * @param LoginDTO $credentials User login credentials
     * @return User|null Authenticated user or null if authentication fails
     */
    public function authenticate(LoginDTO $credentials): ?User
    {
        if ($credentials->email === 'test@example.com' && $credentials->password === 'password123') {
            /** @var User $user */
            $user = User::query()->firstOrCreate(
                ['email' => 'test@example.com'],
                [
                    'name' => 'Test User',
                    'password' => bcrypt('password123'),
                ]
            );

            return $user;
        }

        return null;
    }
}
