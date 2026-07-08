<?php

namespace Uspacy\SDK\Http\Client;

use Saloon\Enums\Method;
use Saloon\Http\Response;
use Uspacy\SDK\Http\Client\Requests\DeleteRequest;
use Uspacy\SDK\Http\Client\Requests\FormPostRequest;
use Uspacy\SDK\Http\Client\Requests\GetRequest;
use Uspacy\SDK\Http\Client\Requests\MultipartPostRequest;
use Uspacy\SDK\Http\Client\Requests\PatchRequest;
use Uspacy\SDK\Http\Client\Requests\PostRequest;
use Uspacy\SDK\Http\Client\Requests\PutRequest;

/**
 * Thin, verb-oriented wrapper around the Saloon connector.
 *
 * This is the PHP counterpart of the JS SDK's generic `HttpClient` (axios instance):
 * services build an endpoint namespace and delegate the actual transport here.
 */
class HttpClient
{
    public function __construct(
        private UspacySDK $connector,
    ) {
    }

    public function get(string $endpoint, array $query = []): Response
    {
        return $this->connector->send(new GetRequest($endpoint, $query));
    }

    public function post(string $endpoint, array $payload = [], array $query = []): Response
    {
        return $this->connector->send(new PostRequest($endpoint, $payload, $query));
    }

    public function patch(string $endpoint, array $payload = [], array $query = []): Response
    {
        return $this->connector->send(new PatchRequest($endpoint, $payload, $query));
    }

    public function put(string $endpoint, array $payload = [], array $query = []): Response
    {
        return $this->connector->send(new PutRequest($endpoint, $payload, $query));
    }

    public function delete(string $endpoint, array $payload = [], array $query = []): Response
    {
        return $this->connector->send(new DeleteRequest($endpoint, $payload, $query));
    }

    public function postForm(string $endpoint, array $payload = []): Response
    {
        return $this->connector->send(new FormPostRequest($endpoint, $payload));
    }

    public function patchForm(string $endpoint, array $payload = []): Response
    {
        return $this->connector->send(new FormPostRequest($endpoint, $payload, Method::PATCH));
    }

    /**
     * @param  array<int, \Saloon\Data\MultipartValue>  $parts
     */
    public function postMultipart(string $endpoint, array $parts = []): Response
    {
        return $this->connector->send(new MultipartPostRequest($endpoint, $parts));
    }

    public function connector(): UspacySDK
    {
        return $this->connector;
    }
}
