<?php

namespace App\Http\Integrations\Uspacy\Requests\Messenger;

use App\DTOs\Messages\UpdateMessageStatusDTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;

use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;

class UpdateMessageStatusRequest extends Request  implements HasBody
{
    use HasJsonBody;

    public function __construct(
        protected string $messageId,
        protected UpdateMessageStatusDTO $payload
    ) {}
    
    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::PATCH;

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return "/messenger/v1/messages/{$this->messageId}/status";
    }

    protected function defaultBody(): array
    {
        return [
            'status' => $this->payload->status?->value,
            'statusText' => $this->payload->message
        ];
    }
}
