<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class PlatformServicesTest extends TestCase
{
    public function test_roles(): void
    {
        $this->sdk->roles()->getRoles();
        $this->assertRequestSent('GET', '/company/v1/roles');

        $this->sdk->roles()->getRole('admin');
        $this->assertRequestSent('GET', '/company/v1/roles/admin');

        $this->sdk->roles()->createRole(['name' => 'Sales']);
        $this->assertRequestSent('POST', '/company/v1/roles/', ['name' => 'Sales']);

        $this->sdk->roles()->getPermissionsFunnels('admin');
        $this->assertRequestSent('GET', '/crm/v1/permissions/funnels', null, ['role' => 'admin']);

        $this->sdk->roles()->updateRolePermissionsFunnels(['x' => 1]);
        $this->assertRequestSent('POST', '/crm/v1/permissions', ['x' => 1]);
    }

    public function test_webhooks_outgoing_and_incoming(): void
    {
        $this->sdk->webhooks()->getWebhooks(1, 20, 'hook');
        $this->assertRequestSent('GET', '/company/v1/webhooks', null, ['page' => 1, 'list' => 20, 'name' => 'hook']);

        $this->sdk->webhooks()->getWebhookById(5, true);
        $this->assertRequestSent('GET', '/company/v1/incoming_webhooks/5/');

        $this->sdk->webhooks()->toggleWebhook(5);
        $this->assertRequestSent('PATCH', '/company/v1/webhooks/5/toggle');

        $this->sdk->webhooks()->deleteSelectedWebhooks([1, 2]);
        $this->assertRequestSent('DELETE', '/company/v1/webhooks', ['ids' => [1, 2]]);
    }

    public function test_oauth_clients(): void
    {
        $this->sdk->oauthClients()->getOAuthClients();
        $this->assertRequestSent('GET', '/company/v1/oauth_clients');

        $this->sdk->oauthClients()->getAvailablePermissions();
        $this->assertRequestSent('GET', '/company/v1/oauth_clients/available_permissions');

        $this->sdk->oauthClients()->updateOAuthClient('abc', ['name' => 'X']);
        $this->assertRequestSent('PATCH', '/company/v1/oauth_clients/abc', ['name' => 'X']);
    }

    public function test_invites(): void
    {
        $this->sdk->invites()->checkInviteByEmail('ada@example.com');
        $this->assertRequestSent('GET', '/company/v1/users/check_invite', null, ['email' => 'ada@example.com']);

        $this->sdk->invites()->createInvitesBatch(['a@x.com', 'b@x.com']);
        $this->assertRequestSent('POST', '/company/v1/invites/email/batch', ['a@x.com', 'b@x.com']);

        $this->sdk->invites()->resendInviteByUserId(7);
        $this->assertRequestSent('PATCH', '/company/v1/invites/email/7/repeat_invitation');

        $this->sdk->invites()->deleteInviteByUserId(7);
        $this->assertRequestSent('DELETE', '/company/v1/invites/email/7');
    }

    public function test_notifications(): void
    {
        $this->sdk->notifications()->getNotifications();
        $this->assertRequestSent('GET', '/notifications/v1/notifications');
    }

    public function test_tasks_timer(): void
    {
        $this->sdk->tasksTimer()->getTimerRealtime();
        $this->assertRequestSent('GET', '/tasks/v1/timer/realtime');

        $this->sdk->tasksTimer()->startTimer('t1');
        $this->assertRequestSent('POST', '/tasks/v1/timer/t1/start');

        $this->sdk->tasksTimer()->createTimer('t1', ['seconds' => 60]);
        $this->assertRequestSent('POST', '/tasks/v1/timer/t1/', ['seconds' => 60]);

        $this->sdk->tasksTimer()->updateTimer('t1', 'e2', ['seconds' => 90]);
        $this->assertRequestSent('PATCH', '/tasks/v1/timer/t1/e2', ['seconds' => 90]);

        $this->sdk->tasksTimer()->deleteTimer('t1', 'e2');
        $this->assertRequestSent('DELETE', '/tasks/v1/timer/t1/e2');
    }

    public function test_history(): void
    {
        $this->sdk->history()->getChangesHistory('crm', 'deals', 42, ['page' => 1, 'action' => 'update']);
        $this->assertRequestSent('GET', '/history/v1/crm/deals/42', null, ['page' => 1, 'action' => 'update']);
    }
}
