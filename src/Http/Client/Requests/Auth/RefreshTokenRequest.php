<?php

namespace Uspacy\SDK\Http\Client\Requests\Auth;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class RefreshTokenRequest extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * Define the HTTP method
     */
    protected Method $method = Method::POST;

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/auth/v1/auth/refresh_token';
    }

    protected function defaultBody(): array
    {
        return [];
    }

    public function createDtoFromResponse(Response $response): Tokens
    {
        $data = $response->json();

        return new Tokens(
            $data['jwt'],
            $data['refreshToken'],
            $data['expireInSeconds'] ?? '',
        );
    }
}
