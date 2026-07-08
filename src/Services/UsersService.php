<?php

namespace Uspacy\SDK\Services;

use Saloon\Data\MultipartValue;
use Saloon\Http\Response;
use Uspacy\SDK\DTOs\Collection;
use Uspacy\SDK\DTOs\Users\ImportRegisteredUserDTO;
use Uspacy\SDK\DTOs\Users\PortalSettingsDTO;
use Uspacy\SDK\DTOs\Users\SearchUsersDTO;
use Uspacy\SDK\DTOs\Users\TwoFaStatusDTO;
use Uspacy\SDK\DTOs\Users\UpdateRolesDTO;
use Uspacy\SDK\DTOs\Users\UpdateUserDTO;
use Uspacy\SDK\DTOs\Users\UserDTO;
use Uspacy\SDK\DTOs\Users\UserFilterDTO;
use Uspacy\SDK\DTOs\Users\UserOnlineStatusesDTO;

/**
 * Users service.
 *
 * Covers user management under the `company/v1` module. Mirrors the Go SDK's
 * `user.go` and the JS SDK's UsersService. Read/write methods return typed DTOs
 * (see the `DTOs\Users` namespace); every DTO also keeps the raw payload.
 */
class UsersService extends Service
{
    private const NAMESPACE = '/company/v1';

    private const USERS = '/company/v1/users';

    /**
     * Get all users (including inactive), no pagination.
     *
     * @return array<int, UserDTO>
     */
    public function getAllUsers(): array
    {
        $data = $this->http->get(self::NAMESPACE . '/users/', ['show' => 'all', 'list' => 'all'])->json() ?? [];

        return array_map([UserDTO::class, 'fromArray'], $data);
    }

    /**
     * Get a page of users.
     *
     * @param  UserFilterDTO|array  $filter  query filter
     * @return Collection<UserDTO>
     */
    public function getUsers(UserFilterDTO|array $filter = []): Collection
    {
        $params = $filter instanceof UserFilterDTO ? $filter->toArray() : $filter;

        return Collection::fromArray(
            $this->http->get(self::NAMESPACE . '/users/', $params)->json() ?? [],
            [UserDTO::class, 'fromArray'],
        );
    }

    /**
     * Get a single user by id.
     *
     * @param  int|string  $id
     */
    public function getUserById($id): UserDTO
    {
        return UserDTO::fromArray($this->http->get(self::NAMESPACE . "/users/{$id}")->json() ?? []);
    }

    /**
     * Update a user.
     *
     * @param  int|string  $id
     * @param  UpdateUserDTO|array  $data
     */
    public function patchUser($id, UpdateUserDTO|array $data): UserDTO
    {
        return $this->doUpdateUser($id, $data);
    }

    /**
     * Update a user (alias of patchUser, matching the JS SDK naming).
     *
     * @param  int|string  $id
     * @param  UpdateUserDTO|array  $data
     */
    public function updateUser($id, UpdateUserDTO|array $data): UserDTO
    {
        return $this->doUpdateUser($id, $data);
    }

    /**
     * Deactivate a user.
     *
     * @param  int|string  $id
     */
    public function deactivateUser($id): UserDTO
    {
        return UserDTO::fromArray($this->http->post(self::USERS . "/{$id}/deactivate")->json() ?? []);
    }

    /**
     * Activate a user.
     *
     * @param  int|string  $id
     */
    public function activateUser($id): UserDTO
    {
        return UserDTO::fromArray($this->http->post(self::USERS . "/{$id}/activate")->json() ?? []);
    }

    /**
     * Update a user's roles.
     *
     * @param  int|string  $id
     * @param  UpdateRolesDTO|array  $roles  a roles DTO, or a plain list of role identifiers
     */
    public function updateRoles($id, UpdateRolesDTO|array $roles): UserDTO
    {
        $body = $roles instanceof UpdateRolesDTO ? $roles->toArray() : ['roles' => $roles];

        return UserDTO::fromArray($this->http->patch(self::USERS . "/{$id}/update_roles", $body)->json() ?? []);
    }

    /**
     * Update a user's position.
     *
     * @param  int|string  $id
     */
    public function updatePosition($id, string $position): UserDTO
    {
        return UserDTO::fromArray(
            $this->http->patch(self::USERS . "/{$id}/update_position", ['position' => $position])->json() ?? [],
        );
    }

    /**
     * Get a user's portal settings.
     *
     * @param  int|string  $id
     */
    public function getPortalSettings($id): PortalSettingsDTO
    {
        return PortalSettingsDTO::fromArray($this->http->get(self::USERS . "/{$id}/settings")->json() ?? []);
    }

    /**
     * Update a user's portal settings.
     *
     * @param  int|string  $id
     */
    public function updatePortalSettings($id, array $settings): PortalSettingsDTO
    {
        return PortalSettingsDTO::fromArray(
            $this->http->patch(self::USERS . "/{$id}/settings", $settings)->json() ?? [],
        );
    }

    /**
     * Get a user's two-factor authentication status.
     *
     * @param  int|string  $id
     */
    public function get2FaStatus($id): TwoFaStatusDTO
    {
        return TwoFaStatusDTO::fromArray($this->http->get(self::USERS . "/{$id}/twofa_status")->json() ?? []);
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
     * @param  SearchUsersDTO|array  $params  search parameters
     * @return Collection<UserDTO>
     */
    public function search(SearchUsersDTO|array $params = []): Collection
    {
        $query = $params instanceof SearchUsersDTO ? $params->toArray() : $params;

        return Collection::fromArray(
            $this->http->get(self::USERS . '/search/', $query)->json() ?? [],
            [UserDTO::class, 'fromArray'],
        );
    }

    /**
     * Upload a user avatar (multipart).
     *
     * @param  string|resource  $file  avatar file contents or stream
     * @param  int|string|null  $userId  target user id (defaults to the current user)
     */
    public function uploadAvatar($file, $userId = null, string $filename = 'avatar'): UserDTO
    {
        $parts = [new MultipartValue(name: 'avatar', value: $file, filename: $filename)];

        if ($userId !== null) {
            $parts[] = new MultipartValue(name: 'userId', value: (string) $userId);
        }

        return UserDTO::fromArray($this->http->postMultipart(self::USERS . '/upload_avatar/', $parts)->json() ?? []);
    }

    /**
     * Get several users by their ids.
     *
     * @param  array  $ids  user ids
     * @return Collection<UserDTO>
     */
    public function getUsersByIds(array $ids): Collection
    {
        return Collection::fromArray(
            $this->http->get(self::USERS, ['ids' => $ids])->json() ?? [],
            [UserDTO::class, 'fromArray'],
        );
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
    public function getUsersOnlineStatuses(): UserOnlineStatusesDTO
    {
        return UserOnlineStatusesDTO::fromArray($this->http->get(self::USERS . '/online')->json() ?? []);
    }

    /**
     * Import/activate a registered user by email invite.
     *
     * @param  ImportRegisteredUserDTO|array  $data
     */
    public function importRegisteredUser(ImportRegisteredUserDTO|array $data): UserDTO
    {
        $payload = $data instanceof ImportRegisteredUserDTO ? $data->toArray() : $data;

        return UserDTO::fromArray(
            $this->http->post(self::NAMESPACE . '/invites/email/import_registered', $payload)->json() ?? [],
        );
    }

    /**
     * @param  int|string  $id
     * @param  UpdateUserDTO|array  $data
     */
    private function doUpdateUser($id, UpdateUserDTO|array $data): UserDTO
    {
        $payload = $data instanceof UpdateUserDTO ? $data->toArray() : $data;

        return UserDTO::fromArray($this->http->patch(self::USERS . "/{$id}", $payload)->json() ?? []);
    }
}
