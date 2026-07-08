<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class UsersServiceTest extends TestCase
{
    public function test_get_all_users_requests_show_all_list_all(): void
    {
        $this->sdk->users()->getAllUsers();

        $this->assertRequestSent('GET', '/company/v1/users/', null, ['show' => 'all', 'list' => 'all']);
    }

    public function test_get_users_paginated(): void
    {
        $this->sdk->users()->getUsers(['page' => 2, 'list' => 20]);

        $this->assertRequestSent('GET', '/company/v1/users/', null, ['page' => 2, 'list' => 20]);
    }

    public function test_get_user_by_id(): void
    {
        $this->sdk->users()->getUserById(7);

        $this->assertRequestSent('GET', '/company/v1/users/7');
    }

    public function test_patch_user(): void
    {
        $this->sdk->users()->patchUser(7, ['position' => 'CTO']);

        $this->assertRequestSent('PATCH', '/company/v1/users/7', ['position' => 'CTO']);
    }

    public function test_import_registered_user(): void
    {
        $this->sdk->users()->importRegisteredUser(['email' => 'ada@example.com']);

        $this->assertRequestSent('POST', '/company/v1/invites/email/import_registered', ['email' => 'ada@example.com']);
    }
}
