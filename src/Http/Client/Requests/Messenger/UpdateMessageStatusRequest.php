<?php

namespace Uspacy\SDK\Http\Client\Requests\Messenger;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Uspacy\SDK\DTOs\Messages\UpdateMessageStatusDTO;

class UpdateMessageStatusRequest extends Request implements HasBody
{
    use HasJsonBody;

    public function __construct(
        protected string $messageId,
        protected UpdateMessageStatusDTO $payload
    ) {
    }

    /**
     * Define the HTTP method
     */
    protected Method $method = Method::PATCH;

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return "/messenger/v1/messages/{$this->messageId}/status";
    }

    protected function defaultBody(): array
    {
        return [
            'status' => $this->payload->status?->value,
            'statusText' => $this->payload->message,
        ];
    }
}
