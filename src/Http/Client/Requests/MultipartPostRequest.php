<?php

namespace Uspacy\SDK\Http\Client\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasMultipartBody;

/**
 * Generic POST request with a `multipart/form-data` body.
 *
 * Accepts a list of {@see \Saloon\Data\MultipartValue} parts, mirroring the JS
 * SDK calls that build a FormData payload (e.g. avatar upload).
 */
class MultipartPostRequest extends Request implements HasBody
{
    use HasMultipartBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $endpoint,
        protected array $parts = [],
    ) {
    }

    public function resolveEndpoint(): string
    {
        return $this->endpoint;
    }

    protected function defaultBody(): array
    {
        return $this->parts;
    }
}
