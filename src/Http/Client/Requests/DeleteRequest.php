<?php

namespace Uspacy\SDK\Http\Client\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Generic DELETE request, optionally carrying a JSON body.
 */
class DeleteRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $endpoint,
        protected array $payload = [],
        protected array $queryParams = [],
    ) {
    }

    public function resolveEndpoint(): string
    {
        return $this->endpoint;
    }

    protected function defaultBody(): array
    {
        return $this->payload;
    }

    protected function defaultQuery(): array
    {
        return \array_filter($this->queryParams, static fn ($value) => $value !== null);
    }
}
