<?php

namespace App\Http\Integrations\Uspacy\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class TestRequest extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * @param  Method  $method
     * @param  string|null  $value
     */
    public function __construct(
        protected Method $method,
        protected ?string $value = null
    ) {
    }

    /**
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/endpoint';
    }

    /**
     * @return string[]
     */
    protected function defaultBody(): array
    {
        return [
            'value' => $this->value
        ];
    }
}