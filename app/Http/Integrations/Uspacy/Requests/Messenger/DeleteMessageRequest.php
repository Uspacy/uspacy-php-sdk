<?php

namespace App\Http\Integrations\Uspacy\Requests\Messenger;

use Saloon\Enums\Method;
use Saloon\Http\Request;

use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;

class DeleteMessageRequest extends Request
{

    public function __construct(
        protected string $messageId
    ) {}
    
    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::DELETE;

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/messenger/v1/messages/' . $this->messageId;
    }
}
