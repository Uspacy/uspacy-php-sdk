<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Groups service.
 *
 * Covers the `groups/v1` module. Mirrors the Go SDK's `groups.go`.
 */
class GroupsService extends Service
{
    private const NAMESPACE = '/groups/v1';

    /**
     * Get a page of groups.
     *
     * @param  array  $params  query parameters (page, list, filters, ...)
     */
    public function getGroups(array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . '/groups', $params);
    }

    /**
     * Create a group (form-encoded payload).
     */
    public function createGroup(array $data): Response
    {
        return $this->http->postForm(self::NAMESPACE . '/groups', $data);
    }

    /**
     * Transfer group ownership / membership.
     */
    public function transferGroup(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/transfer', $data);
    }
}
