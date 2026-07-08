<?php

namespace Uspacy\SDK\Http\Client\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;

/**
 * Generic POST request with an `application/x-www-form-urlencoded` body.
 *
 * Used by endpoints that expect form-encoded payloads (groups, newsfeed, tasks),
 * mirroring the Go SDK's `doPostEncodedForm`.
 */
class FormPostRequest extends Request implements HasBody
{
    use HasFormBody;

    public function __construct(
        protected string $endpoint,
        protected array $payload = [],
        protected Method $httpMethod = Method::POST,
    ) {
        $this->method = $httpMethod;
    }

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return $this->endpoint;
    }

    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
