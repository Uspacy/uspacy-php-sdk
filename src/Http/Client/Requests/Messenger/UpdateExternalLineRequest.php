<?php

namespace Uspacy\SDK\Http\Client\Requests\Messenger;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateExternalLineRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        protected string $id,
        protected array $payload
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/messenger/v1/external-lines/{$this->id}";
    }

    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
