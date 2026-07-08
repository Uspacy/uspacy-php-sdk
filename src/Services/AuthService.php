<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\Http\Client\Requests\Auth\ApplicationSignInRequest;
use Uspacy\SDK\Http\Client\Requests\Auth\RefreshTokenRequest;
use Uspacy\SDK\Http\Client\Requests\Auth\Tokens;

/**
 * Auth service.
 *
 * Covers the `auth/v1` module: application sign-in and token refresh.
 */
class AuthService extends Service
{
    /**
     * Sign in as an application and receive a fresh token pair.
     */
    public function applicationSignIn(string $clientId, string $clientSecret): Tokens
    {
        return $this->http->connector()
            ->send(new ApplicationSignInRequest($clientId, $clientSecret))
            ->dto();
    }

    /**
     * Refresh the current access token.
     */
    public function refreshToken(): Response
    {
        return $this->http->connector()->send(new RefreshTokenRequest());
    }
}
