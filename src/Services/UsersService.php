<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Users service.
 *
 * Covers user management under the `company/v1` module. Mirrors the Go SDK's `user.go`.
 */
class UsersService extends Service
{
    private const NAMESPACE = '/company/v1';

    /**
     * Get all users (including inactive), no pagination.
     */
    public function getAllUsers(): Response
    {
        return $this->http->get(self::NAMESPACE . '/users/', ['show' => 'all', 'list' => 'all']);
    }

    /**
     * Get a page of users.
     *
     * @param  array  $params  query parameters (page, list, show, ...)
     */
    public function getUsers(array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . '/users/', $params);
    }

    /**
     * Get a single user by id.
     *
     * @param  int|string  $id
     */
    public function getUserById($id): Response
    {
        return $this->http->get(self::NAMESPACE . "/users/{$id}");
    }

    /**
     * Update a user.
     *
     * @param  int|string  $id
     */
    public function patchUser($id, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/users/{$id}", $data);
    }

    /**
     * Import/activate a registered user by email invite.
     */
    public function importRegisteredUser(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/invites/email/import_registered', $data);
    }
}
