<?php

namespace Uspacy\SDK\Http\Client\Requests\Settings;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetGeneralSettingsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $domain,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/settings/v1/settings/general';
    }

    protected function defaultQuery(): array
    {
        return [
            'domain' => $this->domain,
        ];
    }
}
