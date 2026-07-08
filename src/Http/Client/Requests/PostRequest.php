<?php

namespace Uspacy\SDK\Http\Client\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Generic POST request with a JSON body.
 *
 * Mirrors the JS SDK's `httpClient.client.post(namespace, body, { params })` call.
 */
class PostRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

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
