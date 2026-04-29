<?php

namespace Uspacy\SDK\Http\Client;

use Saloon\Http\Auth\TokenAuthenticator;
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
        $this->authenticate(new TokenAuthenticator($this->apiToken));
        $this->initRetryConfig();
    }

    public function resolveBaseUrl(): string
    {
        return $this->apiUrl;
    }

    private function initRetryConfig(): void
    {
        $this->tries = \config('uspacy-sdk.retry.tries', 3);
        $this->retryInterval = \config('uspacy-sdk.retry.interval', 1000);
    }
}
