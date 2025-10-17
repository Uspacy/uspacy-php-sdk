<?php

namespace Uspacy\SDK\Http\Client\Requests\Messenger;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetExternalLinesRequest extends Request
{
    /**
     * Define the HTTP method
     */
    protected Method $method = Method::GET;

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/messenger/v1/external-lines';
    }
}
