<?php

namespace App\Services\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Database implementation of user authenticator.
 */
class DatabaseUserAuthenticator implements UserAuthenticatorInterface
{
    /**
     * Authenticate a user with credentials.
     *
     * @param LoginDTO $credentials User login credentials
     * @return User|null Authenticated user or null if authentication fails
     */
    public function authenticate(LoginDTO $credentials): ?User
    {
        $user = User::query()->where('email', $credentials->email)->first();

        if (! $user || ! Hash::check($credentials->password, $user->password)) {
            return null;
        }

        return $user;
    }
}
