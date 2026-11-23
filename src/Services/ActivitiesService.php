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
     * Get free/busy time slots.
     */
    public function getTimeSlots(array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . '/activities/time_slots', $params);
    }

    /**
     * Create a copy of an activity.
     *
     * @param  int|string  $id
     */
    public function replicateActivity($id, array $data = []): Response
    {
        return $this->http->post(self::NAMESPACE . "/activities/{$id}/replicate", $data);
    }

    /**
     * Get a single activity by id.
     *
     * @param  int|string  $id
     */
    public function getActivity($id, array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . "/activities/{$id}", $params);
    }

    /**
     * Update an activity.
     *
     * @param  int|string  $id
     */
    public function patchActivity($id, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/activities/{$id}", $data);
    }

    /**
     * Delete an activity.
     *
     * @param  int|string  $id
     */
    public function deleteActivity($id): Response
    {
        return $this->http->delete(self::NAMESPACE . "/activities/{$id}");
    }

    /**
     * Mass delete activities.
     */
    public function massDeleteActivities(array $data): Response
    {
        return $this->http->delete(self::NAMESPACE . '/activities/mass_deletion', $data);
    }

    /**
     * Mass edit activities.
     */
    public function massEditActivities(array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . '/activities/mass_edit', $data);
    }

    /**
     * Mass reopen activities (status becomes `planned`).
     */
    public function massReopenActivities(array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . '/activities/mass_reopen', $data);
    }
}
