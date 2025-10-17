<?php

namespace Uspacy\SDK\Http\Client\Requests\Files;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetFilesRequest extends Request
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
        return '/files/v1/files';
    }
}
