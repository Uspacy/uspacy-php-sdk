<?php

namespace App\Http\Integrations\Uspacy;

use App\Http\Integrations\Uspacy\Resources\CreateCrmEntityItemResource;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class UspacySDK extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    public function __construct(
        protected ?string $apiUrl = null,
        protected ?string $apiToken = null,
    ){
        $this->withTokenAuth($this->apiToken ?? config('services.uspacy.api_token'));
        $this->apiUrl = $this->apiUrl ?? config('services.uspacy.api_domain');
    }

    /**
     * The Base URL of the API
     *
     * @return string
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
        return [
        ];
    }
}
