<?php

namespace Uspacy\SDK\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Saloon\Contracts\Body\HasBody;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Uspacy\SDK\Http\Client\Requests\DeleteRequest;
use Uspacy\SDK\Http\Client\Requests\FormPostRequest;
use Uspacy\SDK\Http\Client\Requests\GetRequest;
use Uspacy\SDK\Http\Client\Requests\PatchRequest;
use Uspacy\SDK\Http\Client\Requests\PostRequest;
use Uspacy\SDK\Http\Client\Requests\PutRequest;
use Uspacy\SDK\Http\Client\UspacySDK;
use Uspacy\SDK\UspacySDKServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected const BASE_URL = 'https://acme.uspacy.ua';

    protected const TOKEN = 'test-token';

    protected UspacySDK $sdk;

    protected MockClient $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sdk = new UspacySDK(self::BASE_URL, self::TOKEN);
        $this->mock = $this->makeMockClient();
        $this->sdk->withMockClient($this->mock);
    }

    protected function getPackageProviders($app): array
    {
        return [UspacySDKServiceProvider::class];
    }

    /**
     * A mock client that returns a benign 200 response for every generic verb,
     * so services can be exercised offline and their outgoing requests asserted.
     */
    protected function makeMockClient(): MockClient
    {
        $ok = MockResponse::make(['data' => [], 'meta' => []], 200);

        return new MockClient([
            GetRequest::class => $ok,
            PostRequest::class => MockResponse::make(['id' => 1], 201),
            PatchRequest::class => $ok,
            PutRequest::class => $ok,
            DeleteRequest::class => MockResponse::make([], 204),
            FormPostRequest::class => MockResponse::make(['id' => 1], 201),
        ]);
    }

    /**
     * Assert that a request with the given method + endpoint (and optionally body/query) was sent.
     *
     * @param  array<string, mixed>|null  $body
     * @param  array<string, mixed>|null  $query
     */
    protected function assertRequestSent(string $method, string $endpoint, ?array $body = null, ?array $query = null): void
    {
        $this->mock->assertSent(function (Request $request) use ($method, $endpoint, $body, $query): bool {
            if ($request->getMethod()->value !== $method) {
                return false;
            }

            if ($request->resolveEndpoint() !== $endpoint) {
                return false;
            }

            if ($body !== null && (!$request instanceof HasBody || $request->body()->all() !== $body)) {
                return false;
            }

            if ($query !== null && $request->query()->all() !== $query) {
                return false;
            }

            return true;
        });
    }
}
