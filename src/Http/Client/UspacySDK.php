<?php

namespace Uspacy\SDK\Http\Client;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class UspacySDK extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    public function __construct(
        protected string $apiUrl,
        protected string $apiToken,
    ) {
        $this->withTokenAuth($this->apiToken);
    }

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * Default headers for every request
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [

        ];
    }

    /**
     * Default HTTP client options
     *
     * @return string[]
     */
    protected function defaultConfig(): array
    {
        return [];
    }
}
