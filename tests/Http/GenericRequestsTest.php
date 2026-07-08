<?php

namespace Uspacy\SDK\Tests\Http;

use Saloon\Enums\Method;
use Uspacy\SDK\Http\Client\Requests\FormPostRequest;
use Uspacy\SDK\Http\Client\Requests\GetRequest;
use Uspacy\SDK\Http\Client\Requests\PostRequest;
use Uspacy\SDK\Tests\TestCase;

class GenericRequestsTest extends TestCase
{
    public function test_form_post_request_defaults_to_post(): void
    {
        $request = new FormPostRequest('/groups/v1/groups', ['title' => 'Sales']);

        $this->assertSame(Method::POST, $request->getMethod());
    }

    public function test_form_post_request_honours_method_override(): void
    {
        $request = new FormPostRequest('/groups/v1/groups', ['title' => 'Sales'], Method::PATCH);

        $this->assertSame(Method::PATCH, $request->getMethod());
    }

    public function test_get_request_drops_null_query_params(): void
    {
        $pending = $this->sdk->createPendingRequest(
            new GetRequest('/crm/v1/entities/deals/', ['page' => 2, 'list' => null]),
        );

        $this->assertSame(['page' => 2], $pending->query()->all());
    }

    public function test_post_request_sends_json_body_and_query(): void
    {
        $pending = $this->sdk->createPendingRequest(
            new PostRequest('/crm/v1/entities/deals/', ['title' => 'X'], ['dry' => 1]),
        );

        $this->assertSame(['title' => 'X'], $pending->body()->all());
        $this->assertSame(['dry' => 1], $pending->query()->all());
    }
}
