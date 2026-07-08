<?php

namespace Uspacy\SDK\Tests\Services;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Uspacy\SDK\Http\Client\Requests\Auth\ApplicationSignInRequest;
use Uspacy\SDK\Http\Client\Requests\Auth\RefreshTokenRequest;
use Uspacy\SDK\Http\Client\Requests\Auth\Tokens;
use Uspacy\SDK\Tests\TestCase;

class AuthServiceTest extends TestCase
{
    public function test_application_sign_in_returns_tokens_dto(): void
    {
        $this->sdk->withMockClient(new MockClient([
            ApplicationSignInRequest::class => MockResponse::make([
                'jwt' => 'jwt-token',
                'refreshToken' => 'refresh-token',
                'expireInSeconds' => '3600',
            ], 200),
        ]));

        $tokens = $this->sdk->auth()->applicationSignIn('client-id', 'client-secret');

        $this->assertInstanceOf(Tokens::class, $tokens);
        $this->assertSame('jwt-token', $tokens->token);
        $this->assertSame('refresh-token', $tokens->refreshToken);
        $this->assertSame('3600', $tokens->expiresIn);
    }

    public function test_refresh_token_returns_tokens_dto(): void
    {
        $this->sdk->withMockClient(new MockClient([
            RefreshTokenRequest::class => MockResponse::make([
                'jwt' => 'new-jwt',
                'refreshToken' => 'new-refresh',
                'expireInSeconds' => '7200',
            ], 200),
        ]));

        $tokens = $this->sdk->auth()->refreshToken();

        $this->assertInstanceOf(Tokens::class, $tokens);
        $this->assertSame('new-jwt', $tokens->token);
        $this->assertSame('new-refresh', $tokens->refreshToken);
        $this->assertSame('7200', $tokens->expiresIn);
    }

    public function test_refresh_token_tolerates_missing_expiry(): void
    {
        $this->sdk->withMockClient(new MockClient([
            RefreshTokenRequest::class => MockResponse::make([
                'jwt' => 'new-jwt',
                'refreshToken' => 'new-refresh',
            ], 200),
        ]));

        $tokens = $this->sdk->auth()->refreshToken();

        $this->assertSame('', $tokens->expiresIn);
    }
}
