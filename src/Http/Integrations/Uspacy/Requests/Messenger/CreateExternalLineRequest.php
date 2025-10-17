<?php

namespace Uspacy\SDK\Http\Integrations\Uspacy\Requests\Messenger;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;

class CreateExternalLineRequest extends Request  implements HasBody
{
    use HasJsonBody;
    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::POST;

    public function __construct(
        protected array $payload
    ) {}

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/messenger/v1/external-lines';
    }

    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
