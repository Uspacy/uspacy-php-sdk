<?php

namespace Uspacy\SDK\Http\Client\Requests\Messenger;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Uspacy\SDK\Exceptions\MessageDuplicationException;

class CreateMessageRequest extends Request implements HasBody
{
    use HasJsonBody;

    public function __construct(
        protected array $payload
    ) {
    }

    /**
     * Define the HTTP method
     */
    protected Method $method = Method::POST;

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/messenger/v1/messages';
    }

    protected function defaultBody(): array
    {
        return $this->payload;
    }

    public function getRequestException(Response $response, ?\Throwable $senderException): ?\Throwable
    {
        $json = $response->json();

        if (isset($json['message']) && str_contains($json['message'], 'E11000 duplicate key error')) {
            return new MessageDuplicationException($response, null, 0, $senderException);
        }

        return parent::getRequestException($response, $senderException);
    }
}
