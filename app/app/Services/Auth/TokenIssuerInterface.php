<?php

namespace App\Services\Auth;

use App\Models\User;

/**
 * Interface for token issuers.
 */
interface TokenIssuerInterface
{
    /**
     * Issue a token for a user.
     *
     * @param User $user The user to issue a token for
     * @param string $tokenName The name of the token
     * @return array Token information with accessToken, expiresIn
     */
    public function issueToken(User $user, string $tokenName = 'API Token'): array;
}
