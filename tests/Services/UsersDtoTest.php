<?php

namespace Uspacy\SDK\Tests\Services;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Uspacy\SDK\DTOs\Collection;
use Uspacy\SDK\DTOs\Users\PortalSettingsDTO;
use Uspacy\SDK\DTOs\Users\TwoFaStatusDTO;
use Uspacy\SDK\DTOs\Users\UpdateRolesDTO;
use Uspacy\SDK\DTOs\Users\UpdateUserDTO;
use Uspacy\SDK\DTOs\Users\UserDTO;
use Uspacy\SDK\DTOs\Users\UserFilterDTO;
use Uspacy\SDK\DTOs\Users\UserOnlineStatusesDTO;
use Uspacy\SDK\Http\Client\Requests\GetRequest;
use Uspacy\SDK\Http\Client\Requests\PatchRequest;
use Uspacy\SDK\Tests\TestCase;

class UsersDtoTest extends TestCase
{
    public function test_get_user_by_id_hydrates_user_dto_and_keeps_raw(): void
    {
        $this->mockGet([
            'id' => 7,
            'firstName' => 'Ada',
            'lastName' => 'Lovelace',
            'active' => true,
            'roles' => ['admin'],
            'some_custom_field' => 'kept',
        ]);

        $user = $this->sdk->users()->getUserById(7);

        $this->assertInstanceOf(UserDTO::class, $user);
        $this->assertSame(7, $user->id);
        $this->assertSame('Ada', $user->firstName);
        $this->assertTrue($user->active);
        $this->assertSame(['admin'], $user->roles);
        $this->assertSame('kept', $user->raw['some_custom_field']);
    }

    public function test_custom_fields_are_readable_via_get_and_has(): void
    {
        $this->mockGet([
            'id' => 7,
            'firstName' => 'Ada',
            'customfield_1' => 'value one',
            'customfield_2' => ['nested' => true],
        ]);

        $user = $this->sdk->users()->getUserById(7);

        $this->assertTrue($user->has('customfield_1'));
        $this->assertSame('value one', $user->get('customfield_1'));
        $this->assertSame(['nested' => true], $user->get('customfield_2'));
        $this->assertFalse($user->has('customfield_404'));
        $this->assertSame('fallback', $user->get('customfield_404', 'fallback'));
    }

    public function test_get_users_hydrates_paginated_collection(): void
    {
        $this->mockGet([
            'data' => [
                ['id' => 1, 'firstName' => 'A'],
                ['id' => 2, 'firstName' => 'B'],
            ],
            'meta' => ['total' => 2, 'page' => 1, 'list' => 20],
        ]);

        $result = $this->sdk->users()->getUsers();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result->data);
        $this->assertInstanceOf(UserDTO::class, $result->data[0]);
        $this->assertSame(2, $result->data[1]->id);
        $this->assertSame(2, $result->meta->total);
        $this->assertSame(1, $result->meta->page);
    }

    public function test_get_users_accepts_a_filter_dto(): void
    {
        $this->sdk->users()->getUsers(new UserFilterDTO(page: 3, list: 50, show: 'all', extra: ['q' => 'x']));

        $this->mock->assertSent(fn (GetRequest $r) => $r->query()->all() === ['page' => 3, 'list' => 50, 'show' => 'all', 'q' => 'x']);
    }

    public function test_two_fa_and_portal_settings_dtos(): void
    {
        $this->mockGet(['enabled' => true]);
        $status = $this->sdk->users()->get2FaStatus(7);
        $this->assertInstanceOf(TwoFaStatusDTO::class, $status);
        $this->assertTrue($status->enabled);

        $this->mockGet(['lang' => 'uk', 'timezone' => 'Europe/Kyiv', 'availableCurrencies' => ['UAH', 'USD']]);
        $settings = $this->sdk->users()->getPortalSettings(7);
        $this->assertInstanceOf(PortalSettingsDTO::class, $settings);
        $this->assertSame('uk', $settings->lang);
        $this->assertSame(['UAH', 'USD'], $settings->availableCurrencies);
    }

    public function test_online_statuses_hydrate_map(): void
    {
        $this->mockGet([
            '7' => ['isOnline' => true, 'lastSeenAt' => 123],
            '8' => ['isOnline' => false, 'lastSeenAt' => 456],
        ]);

        $statuses = $this->sdk->users()->getUsersOnlineStatuses();

        $this->assertInstanceOf(UserOnlineStatusesDTO::class, $statuses);
        $this->assertTrue($statuses->statuses['7']->isOnline);
        $this->assertSame(456, $statuses->statuses['8']->lastSeenAt);
    }

    public function test_patch_user_sends_dto_body_and_returns_user_dto(): void
    {
        $this->sdk->withMockClient(new MockClient([
            PatchRequest::class => MockResponse::make(['id' => 7, 'position' => 'CTO'], 200),
        ]));

        $user = $this->sdk->users()->patchUser(7, new UpdateUserDTO(position: 'CTO', extra: ['city' => 'Kyiv']));

        $this->assertInstanceOf(UserDTO::class, $user);
        $this->assertSame('CTO', $user->position);
        $this->sdk->getMockClient()->assertSent(fn (PatchRequest $r) => $r->body()->all() === ['position' => 'CTO', 'city' => 'Kyiv']);
    }

    public function test_update_roles_accepts_dto_and_plain_array(): void
    {
        $this->sdk->withMockClient(new MockClient([
            PatchRequest::class => MockResponse::make(['id' => 7], 200),
        ]));

        $this->sdk->users()->updateRoles(7, new UpdateRolesDTO(['admin', 'manager']));
        $this->sdk->getMockClient()->assertSent(fn (PatchRequest $r) => $r->body()->all() === ['roles' => ['admin', 'manager']]);

        $this->sdk->users()->updateRoles(7, ['viewer']);
        $this->sdk->getMockClient()->assertSent(fn (PatchRequest $r) => $r->body()->all() === ['roles' => ['viewer']]);
    }

    public function test_empty_or_null_response_body_does_not_throw(): void
    {
        // An empty body decodes to null; DTO hydration must tolerate it.
        $this->sdk->withMockClient(new MockClient([
            GetRequest::class => MockResponse::make('', 204),
        ]));

        $user = $this->sdk->users()->getUserById(7);
        $this->assertInstanceOf(UserDTO::class, $user);
        $this->assertNull($user->id);

        $page = $this->sdk->users()->getUsers();
        $this->assertInstanceOf(Collection::class, $page);
        $this->assertSame([], $page->data);

        $this->assertSame([], $this->sdk->users()->getAllUsers());

        $statuses = $this->sdk->users()->getUsersOnlineStatuses();
        $this->assertInstanceOf(UserOnlineStatusesDTO::class, $statuses);
        $this->assertSame([], $statuses->statuses);
    }

    private function mockGet(array $payload): void
    {
        $this->sdk->withMockClient(new MockClient([
            GetRequest::class => MockResponse::make($payload, 200),
        ]));
    }
}
