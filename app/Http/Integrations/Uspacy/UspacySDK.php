<?php

namespace App\Http\Integrations\Uspacy;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class UspacySDK extends Connector
{
    use AcceptsJson;

    public function __construct(
        protected string $apiUrl,
        protected string $apiToken,
    ){
       $this->withTokenAuth($this->apiToken); 
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
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
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
