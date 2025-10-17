<?php

namespace Uspacy\SDK\Http\Client\Requests\Auth;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ApplicationSignInRequest extends Request implements HasBody
{
    use HasJsonBody;

    public function __construct(
        protected string $clientId,
        protected string $clientSecret,
    ) {
    }

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
        return '/auth/v1/auth/app_sign_in';
    }

    protected function defaultBody(): array
    {
        return [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];
    }

    public function createDtoFromResponse(Response $response): Tokens
    {
        $data = $response->json();

        return new Tokens(
            $data['jwt'],
            $data['refreshToken'],
            $data['expireInSeconds']
        );
    }
}
