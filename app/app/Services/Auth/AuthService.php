<?php

namespace App\Services\Auth;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\LoginResponseDTO;
use App\Models\User;

/**
 * Service for authentication operations.
 */
class AuthService implements AuthServiceInterface
{
    /**
     * The user authenticator service.
     *
     * @var UserAuthenticatorInterface
     */
    private UserAuthenticatorInterface $userAuthenticator;

    /**
     * The token issuer service.
     *
     * @var TokenIssuerInterface
     */
    private TokenIssuerInterface $tokenIssuer;

    /**
     * AuthService constructor.
     *
     * @param UserAuthenticatorInterface $userAuthenticator
     * @param TokenIssuerInterface $tokenIssuer
     */
    public function __construct(
        UserAuthenticatorInterface $userAuthenticator,
        TokenIssuerInterface $tokenIssuer
    ) {
        $this->userAuthenticator = $userAuthenticator;
        $this->tokenIssuer = $tokenIssuer;
    }

    /**
     * Authenticate a user with credentials and return an access token.
     *
     * @param LoginDTO $credentials User login credentials
     * @return LoginResponseDTO|null Login response or null if authentication fails
     */
    public function login(LoginDTO $credentials): ?LoginResponseDTO
    {
        $user = $this->userAuthenticator->authenticate($credentials);

        if ($user === null) {
            return null;
        }

        $tokenInfo = $this->tokenIssuer->issueToken($user);

        return new LoginResponseDTO(
            accessToken: $tokenInfo['accessToken'],
            tokenType: 'Bearer',
            expiresIn: $tokenInfo['expiresIn'],
            user: $user
        );
    }
}
