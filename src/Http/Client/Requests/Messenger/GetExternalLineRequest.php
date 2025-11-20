<?php

namespace Uspacy\SDK\Http\Client\Requests\Messenger;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetExternalLineRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $id)
    {
    }

    public function resolveEndpoint(): string
    {
        return "/messenger/v1/external-lines/{$this->id}";
    }
}
