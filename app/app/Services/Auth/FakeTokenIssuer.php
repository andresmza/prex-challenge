<?php

namespace App\Services\Auth;

use App\Models\User;

/**
 * Fake implementation of the token issuer for testing.
 */
class FakeTokenIssuer implements TokenIssuerInterface
{
    /**
     * Issue a fake token for testing purposes.
     *
     * @param User $user The user to issue a token for
     * @param string $tokenName The name of the token
     * @return array Token information with accessToken, expiresIn
     */
    public function issueToken(User $user, string $tokenName = 'API Token'): array
    {
        return [
            'accessToken' => 'test-token',
            'expiresIn' => 1800,
        ];
    }
}
