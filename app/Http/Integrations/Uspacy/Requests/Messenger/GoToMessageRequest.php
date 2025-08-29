<?php

namespace App\Http\Integrations\Uspacy\Requests\Messenger;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GoToMessageRequest extends Request
{
    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected string $messageId
    ) {
    }

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return "/messenger/v1/messages/{$this->messageId}/goToMessage";
    }

}
