<?php

namespace App\Http\Integrations\Uspacy\Requests\Messenger;

use Saloon\Enums\Method;
use Saloon\Http\Request;

use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;

class CreateChatRequest extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * @param  array  $payload
     */
    public function __construct(
        protected array $payload
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
        return '/messenger/v1/chats';
    }

    /**
     * @return array
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
