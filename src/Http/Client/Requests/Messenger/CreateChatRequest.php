<?php

namespace Uspacy\SDK\Http\Client\Requests\Messenger;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateChatRequest extends Request implements HasBody
{
    use HasJsonBody;

    public function __construct(
        protected array $payload
    ) {}

    /**
     * Define the HTTP method
     */
    protected Method $method = Method::POST;

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/messenger/v1/chats';
    }

    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
