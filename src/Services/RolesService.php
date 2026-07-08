<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Roles & permissions service.
 *
 * Mirrors the JS SDK's RolesService: roles under `/company/v1` and CRM funnel
 * permissions under `/crm/v1`.
 */
class RolesService extends Service
{
    private const NAMESPACE = '/company/v1';

    private const CRM_NAMESPACE = '/crm/v1';

    /**
     * Get all roles.
     */
    public function getRoles(): Response
    {
        return $this->http->get(self::NAMESPACE . '/roles');
    }

    /**
     * Get a role by id.
     *
     * @param  int|string  $id
     */
    public function getRole($id): Response
    {
        return $this->http->get(self::NAMESPACE . "/roles/{$id}");
    }

    /**
     * Create a role.
     */
    public function createRole(array $body): Response
    {
        return $this->http->post(self::NAMESPACE . '/roles/', $body);
    }

    /**
     * Update a role.
     *
     * @param  int|string  $id
     */
    public function updateRole($id, array $body): Response
    {
        return $this->http->patch(self::NAMESPACE . "/roles/{$id}", $body);
    }

    /**
     * Delete a role.
     *
     * @param  int|string  $id
     */
    public function deleteRole($id): Response
    {
        return $this->http->delete(self::NAMESPACE . "/roles/{$id}");
    }

    /**
     * Get the full permissions catalog.
     */
    public function getPermissions(): Response
    {
        return $this->http->get(self::NAMESPACE . '/permissions');
    }

    /**
     * Get CRM funnel permissions, optionally for a specific role.
     */
    public function getPermissionsFunnels(?string $role = null): Response
    {
        return $this->http->get(self::CRM_NAMESPACE . '/permissions/funnels', ['role' => $role]);
    }

    /**
     * Update CRM funnel permissions for roles.
     */
    public function updateRolePermissionsFunnels(array $body): Response
    {
        return $this->http->post(self::CRM_NAMESPACE . '/permissions', $body);
    }
}
