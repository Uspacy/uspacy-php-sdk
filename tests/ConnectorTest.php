<?php

namespace Uspacy\SDK\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Uspacy\SDK\Http\Client\HttpClient;
use Uspacy\SDK\Http\Client\Requests\GetRequest;
use Uspacy\SDK\Services;

class ConnectorTest extends TestCase
{
    public function test_it_resolves_the_base_url(): void
    {
        $this->assertSame(self::BASE_URL, $this->sdk->resolveBaseUrl());
    }

    public function test_it_exposes_a_shared_http_client(): void
    {
        $this->assertInstanceOf(HttpClient::class, $this->sdk->http());
        $this->assertSame($this->sdk->http(), $this->sdk->http(), 'http() should be memoised');
    }

    public function test_it_authenticates_with_a_bearer_token(): void
    {
        $pending = $this->sdk->createPendingRequest(new GetRequest('/crm/v1/entity'));

        $this->assertSame('Bearer ' . self::TOKEN, $pending->headers()->get('Authorization'));
    }

    #[DataProvider('serviceAccessorProvider')]
    public function test_service_accessors_return_the_expected_service(string $accessor, string $class): void
    {
        $this->assertInstanceOf($class, $this->sdk->{$accessor}());
    }

    public static function serviceAccessorProvider(): array
    {
        return [
            ['crm', Services\CrmService::class],
            ['smartObjects', Services\SmartObjectsService::class],
            ['tasks', Services\TasksService::class],
            ['activities', Services\ActivitiesService::class],
            ['users', Services\UsersService::class],
            ['departments', Services\DepartmentsService::class],
            ['groups', Services\GroupsService::class],
            ['comments', Services\CommentsService::class],
            ['newsFeed', Services\NewsFeedService::class],
            ['emails', Services\EmailsService::class],
            ['files', Services\FilesService::class],
            ['messenger', Services\MessengerService::class],
            ['settings', Services\SettingsService::class],
            ['auth', Services\AuthService::class],
        ];
    }
}
