<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Http\Client\Requests\MultipartPostRequest;
use Uspacy\SDK\Tests\TestCase;

class UsersServiceExpandedTest extends TestCase
{
    public function test_activate_and_deactivate(): void
    {
        $this->sdk->users()->deactivateUser(7);
        $this->assertRequestSent('POST', '/company/v1/users/7/deactivate');

        $this->sdk->users()->activateUser(7);
        $this->assertRequestSent('POST', '/company/v1/users/7/activate');
    }

    public function test_roles_and_position(): void
    {
        $this->sdk->users()->updateRoles(7, ['admin', 'manager']);
        $this->assertRequestSent('PATCH', '/company/v1/users/7/update_roles', ['roles' => ['admin', 'manager']]);

        $this->sdk->users()->updatePosition(7, 'CTO');
        $this->assertRequestSent('PATCH', '/company/v1/users/7/update_position', ['position' => 'CTO']);
    }

    public function test_portal_settings_and_2fa(): void
    {
        $this->sdk->users()->getPortalSettings(7);
        $this->assertRequestSent('GET', '/company/v1/users/7/settings');

        $this->sdk->users()->updatePortalSettings(7, ['theme' => 'dark']);
        $this->assertRequestSent('PATCH', '/company/v1/users/7/settings', ['theme' => 'dark']);

        $this->sdk->users()->get2FaStatus(7);
        $this->assertRequestSent('GET', '/company/v1/users/7/twofa_status');

        $this->sdk->users()->disable2Fa(7);
        $this->assertRequestSent('GET', '/company/v1/users/7/twofa_disable');
    }

    public function test_search_ids_link_online(): void
    {
        $this->sdk->users()->search(['q' => 'ada']);
        $this->assertRequestSent('GET', '/company/v1/users/search/', null, ['q' => 'ada']);

        $this->sdk->users()->getUsersByIds([1, 2, 3]);
        $this->assertRequestSent('GET', '/company/v1/users', null, ['ids' => [1, 2, 3]]);

        $this->sdk->users()->getUserRegisterLink(7);
        $this->assertRequestSent('GET', '/company/v1/users/7/register_link');

        $this->sdk->users()->getUsersOnlineStatuses();
        $this->assertRequestSent('GET', '/company/v1/users/online');
    }

    public function test_upload_avatar_uses_multipart(): void
    {
        $this->sdk->users()->uploadAvatar('binary-data', 7, 'me.png');

        $this->mock->assertSent(MultipartPostRequest::class);
        $this->mock->assertSent(fn ($request) => $request->resolveEndpoint() === '/company/v1/users/upload_avatar/');
    }
}
