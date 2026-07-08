<?php

namespace Uspacy\SDK\Http\Client\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Generic GET request.
 *
 * Mirrors the JS SDK's `httpClient.client.get(namespace, { params })` call.
 */
class GetRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $endpoint,
        protected array $queryParams = [],
    ) {
    }

    public function resolveEndpoint(): string
    {
        return $this->endpoint;
    }

    protected function defaultQuery(): array
    {
        return \array_filter($this->queryParams, static fn ($value) => $value !== null);
    }
}
