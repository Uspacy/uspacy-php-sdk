<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Activities service.
 *
 * Covers the `activities/v1` module. Mirrors the Go SDK's `activities.go`.
 */
class ActivitiesService extends Service
{
    private const NAMESPACE = '/activities/v1';

    /**
     * Get a page of activities.
     *
     * @param  array  $params  query parameters (page, list, filters, ...)
     */
    public function getActivities(array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . '/activities', $params);
    }

    /**
     * Create an activity.
     */
    public function createActivity(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/activities', $data);
    }

    /**
     * Get a single activity by id.
     *
     * @param  int|string  $id
     */
    public function getActivity($id, array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . "/entities/{$id}", $params);
    }

    /**
     * Update an activity.
     *
     * @param  int|string  $id
     */
    public function patchActivity($id, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/entities/{$id}", $data);
    }

    /**
     * Delete an activity.
     *
     * @param  int|string  $id
     */
    public function deleteActivity($id): Response
    {
        return $this->http->delete(self::NAMESPACE . "/entities/{$id}");
    }

    /**
     * Mass delete activities.
     */
    public function massDeleteActivities(array $data): Response
    {
        return $this->http->delete(self::NAMESPACE . '/entities/mass_deletion', $data);
    }
}
