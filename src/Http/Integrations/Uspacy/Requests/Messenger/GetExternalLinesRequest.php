<?php

namespace Uspacy\SDK\Http\Integrations\Uspacy\Requests\Messenger;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetExternalLinesRequest extends Request
{
    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::GET;

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/messenger/v1/external-lines';
    }

}
