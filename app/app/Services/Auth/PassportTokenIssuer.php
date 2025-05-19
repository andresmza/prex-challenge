<?php

namespace App\Services\Auth;

use App\Models\User;
use Laravel\Passport\PersonalAccessTokenResult;

/**
 * Passport-based implementation of TokenIssuerInterface.
 */
class PassportTokenIssuer implements TokenIssuerInterface
{
    /**
     * Issue a token for a user.
     *
     * @param  User    $user      The user to issue a token for
     * @param  string  $tokenName The name of the token
     * @return array             Token information with accessToken and expiresIn
     */
    public function issueToken(User $user, string $tokenName = 'API Token'): array
    {

        /** @var PersonalAccessTokenResult $tokenResult */
        $tokenResult = $user->createToken($tokenName);

        return [
            'accessToken' => $tokenResult->accessToken,
            'expiresIn' => config('passport.access_token_expire_minutes') * 60,
        ];
    }
}
