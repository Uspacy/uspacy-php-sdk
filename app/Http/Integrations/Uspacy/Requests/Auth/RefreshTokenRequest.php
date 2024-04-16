<?php

namespace App\Http\Integrations\Uspacy\Requests\Auth;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;

class RefreshTokenRequest extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::POST;

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/auth/v1/auth/refresh_token';
    }

    /**
     * @return array
     */
    protected function defaultBody(): array
    {
        return [];
    }
}
