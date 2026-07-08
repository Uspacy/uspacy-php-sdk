<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Departments service.
 *
 * Covers departments under the `company/v1` module. Mirrors the Go SDK's `departments.go`.
 */
class DepartmentsService extends Service
{
    private const NAMESPACE = '/company/v1';

    /**
     * Get all departments.
     */
    public function getDepartments(): Response
    {
        return $this->http->get(self::NAMESPACE . '/departments/');
    }

    /**
     * Create a department.
     */
    public function createDepartment(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/departments/', $data);
    }

    /**
     * Update a department.
     *
     * @param  int|string  $departmentId
     */
    public function patchDepartment($departmentId, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/departments/{$departmentId}", $data);
    }

    /**
     * Add users to a department.
     *
     * @param  int|string  $departmentId
     * @param  array  $userIds  list of user ids
     */
    public function addUsers($departmentId, array $userIds): Response
    {
        return $this->http->patch(self::NAMESPACE . "/departments/{$departmentId}/addUsers", $userIds);
    }
}
