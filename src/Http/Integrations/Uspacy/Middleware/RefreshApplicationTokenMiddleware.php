<?php

namespace Uspacy\SDK\Http\Integrations\Uspacy\Middleware;

use Saloon\Contracts\ResponseMiddleware;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Response;
use Uspacy\SDK\Http\Integrations\Uspacy\Requests\Auth\ApplicationSignInRequest;
use Uspacy\SDK\Http\Integrations\Uspacy\Requests\Auth\Tokens;

class RefreshApplicationTokenMiddleware implements ResponseMiddleware
{

    private $callback;

    /**
     * @param callable(Tokens): void $callback
     */
    public function __construct(
        private string $clientId,
        private string $clientSecret,
        callable $callback
    ) {
        $this->callback = $callback;
    }

    public function __invoke(Response $response): Response {
        if ($response->status() !== 401) {
            return $response;
        }

        $connector = $response->getConnector();
        $tokensResponse = $connector->send(
            new ApplicationSignInRequest($this->clientId, $this->clientSecret)
        );

        /** @var Tokens $tokens */
        $tokens = $tokensResponse->dto();

        $connector->authenticate(new TokenAuthenticator($tokens->token));

        ($this->callback)($tokens);

        return $connector->send($response->getRequest());
    }
}
