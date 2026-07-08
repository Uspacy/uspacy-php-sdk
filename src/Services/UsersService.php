<?php

namespace Uspacy\SDK\Services;

use Saloon\Data\MultipartValue;
use Saloon\Http\Response;

/**
 * Users service.
 *
 * Covers user management under the `company/v1` module. Mirrors the Go SDK's
 * `user.go` and the JS SDK's UsersService.
 */
class UsersService extends Service
{
    private const NAMESPACE = '/company/v1';

    private const USERS = '/company/v1/users';

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
     * Update a user (alias of patchUser, matching the JS SDK naming).
     *
     * @param  int|string  $id
     */
    public function updateUser($id, array $data): Response
    {
        return $this->http->patch(self::USERS . "/{$id}", $data);
    }

    /**
     * Deactivate a user.
     *
     * @param  int|string  $id
     */
    public function deactivateUser($id): Response
    {
        return $this->http->post(self::USERS . "/{$id}/deactivate");
    }

    /**
     * Activate a user.
     *
     * @param  int|string  $id
     */
    public function activateUser($id): Response
    {
        return $this->http->post(self::USERS . "/{$id}/activate");
    }

    /**
     * Update a user's roles.
     *
     * @param  int|string  $id
     * @param  array  $roles  role identifiers
     */
    public function updateRoles($id, array $roles): Response
    {
        return $this->http->patch(self::USERS . "/{$id}/update_roles", ['roles' => $roles]);
    }

    /**
     * Update a user's position.
     *
     * @param  int|string  $id
     */
    public function updatePosition($id, string $position): Response
    {
        return $this->http->patch(self::USERS . "/{$id}/update_position", ['position' => $position]);
    }

    /**
     * Get a user's portal settings.
     *
     * @param  int|string  $id
     */
    public function getPortalSettings($id): Response
    {
        return $this->http->get(self::USERS . "/{$id}/settings");
    }

    /**
     * Update a user's portal settings.
     *
     * @param  int|string  $id
     */
    public function updatePortalSettings($id, array $settings): Response
    {
        return $this->http->patch(self::USERS . "/{$id}/settings", $settings);
    }

    /**
     * Get a user's two-factor authentication status.
     *
     * @param  int|string  $id
     */
    public function get2FaStatus($id): Response
    {
        return $this->http->get(self::USERS . "/{$id}/twofa_status");
    }

    /**
     * Disable two-factor authentication for a user.
     *
     * @param  int|string  $id
     */
    public function disable2Fa($id): Response
    {
        return $this->http->get(self::USERS . "/{$id}/twofa_disable");
    }

    /**
     * Search users.
     *
     * @param  array  $params  search parameters
     */
    public function search(array $params = []): Response
    {
        return $this->http->get(self::USERS . '/search/', $params);
    }

    /**
     * Upload a user avatar (multipart).
     *
     * @param  string|resource  $file  avatar file contents or stream
     * @param  int|string|null  $userId  target user id (defaults to the current user)
     */
    public function uploadAvatar($file, $userId = null, string $filename = 'avatar'): Response
    {
        $parts = [new MultipartValue(name: 'avatar', value: $file, filename: $filename)];

        if ($userId !== null) {
            $parts[] = new MultipartValue(name: 'userId', value: (string) $userId);
        }

        return $this->http->postMultipart(self::USERS . '/upload_avatar/', $parts);
    }

    /**
     * Get several users by their ids.
     *
     * @param  array  $ids  user ids
     */
    public function getUsersByIds(array $ids): Response
    {
        return $this->http->get(self::USERS, ['ids' => $ids]);
    }

    /**
     * Get a user's registration link.
     *
     * @param  int|string  $id
     */
    public function getUserRegisterLink($id): Response
    {
        return $this->http->get(self::USERS . "/{$id}/register_link");
    }

    /**
     * Get online statuses for all users.
     */
    public function getUsersOnlineStatuses(): Response
    {
        return $this->http->get(self::USERS . '/online');
    }

    /**
     * Import/activate a registered user by email invite.
     */
    public function importRegisteredUser(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/invites/email/import_registered', $data);
    }
}
