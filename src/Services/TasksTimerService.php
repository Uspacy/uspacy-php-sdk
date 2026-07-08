<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Tasks timer service.
 *
 * Mirrors the JS SDK's TasksTimerService (`/tasks/v1/timer`).
 */
class TasksTimerService extends Service
{
    private const NAMESPACE = '/tasks/v1/timer';

    /**
     * Get the realtime timer state.
     */
    public function getTimerRealtime(): Response
    {
        return $this->http->get(self::NAMESPACE . '/realtime');
    }

    /**
     * Get the timer entries for a task.
     *
     * @param  int|string  $taskId
     */
    public function getTimerList($taskId): Response
    {
        return $this->http->get(self::NAMESPACE . "/{$taskId}/");
    }

    /**
     * Start the timer for a task.
     *
     * @param  int|string  $taskId
     */
    public function startTimer($taskId): Response
    {
        return $this->http->post(self::NAMESPACE . "/{$taskId}/start");
    }

    /**
     * Stop the timer for a task.
     *
     * @param  int|string  $taskId
     */
    public function stopTimer($taskId): Response
    {
        return $this->http->post(self::NAMESPACE . "/{$taskId}/stop");
    }

    /**
     * Create a manual timer entry for a task.
     *
     * @param  int|string  $taskId
     */
    public function createTimer($taskId, array $body): Response
    {
        return $this->http->post(self::NAMESPACE . "/{$taskId}/", $body);
    }

    /**
     * Update a timer entry.
     *
     * @param  int|string  $taskId
     * @param  int|string  $timerId
     */
    public function updateTimer($taskId, $timerId, array $body): Response
    {
        return $this->http->patch(self::NAMESPACE . "/{$taskId}/{$timerId}", $body);
    }

    /**
     * Delete a timer entry.
     *
     * @param  int|string  $taskId
     * @param  int|string  $timerId
     */
    public function deleteTimer($taskId, $timerId): Response
    {
        return $this->http->delete(self::NAMESPACE . "/{$taskId}/{$timerId}");
    }
}
