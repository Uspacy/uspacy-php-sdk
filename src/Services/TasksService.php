<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Tasks service.
 *
 * Covers the `tasks/v1` module: tasks CRUD, kanban stages, custom fields and
 * recurring templates. Mirrors the Go SDK's `tasks.go`.
 */
class TasksService extends Service
{
    private const NAMESPACE = '/tasks/v1';

    /**
     * Get a page of tasks.
     *
     * @param  array  $params  query parameters (page, list, filters, ...)
     */
    public function getTasks(array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . '/tasks', $params);
    }

    /**
     * Create a task.
     */
    public function createTask(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/tasks', $data);
    }

    /**
     * Update a task.
     *
     * @param  int|string  $taskId
     */
    public function patchTask($taskId, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/tasks/{$taskId}", $data);
    }

    /**
     * Mark a task as ready (completed).
     *
     * @param  int|string  $taskId
     */
    public function markTaskReady($taskId): Response
    {
        return $this->http->patch(self::NAMESPACE . "/tasks/{$taskId}/ready");
    }

    /**
     * Transfer (reassign) tasks.
     */
    public function transferTasks(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/transfer', $data);
    }

    /**
     * Get the custom fields available for tasks.
     */
    public function getTaskFields(): Response
    {
        return $this->http->get(self::NAMESPACE . '/custom_fields/tasks/fields');
    }

    /**
     * Get the kanban stages for tasks.
     *
     * @param  array  $params  query parameters (e.g. groupId)
     */
    public function getKanbanStages(array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . '/stages', $params);
    }

    /**
     * Create a kanban stage.
     */
    public function createStage(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/stages', $data);
    }

    /**
     * Delete a kanban stage.
     *
     * @param  int|string  $stageId
     */
    public function deleteStage($stageId): Response
    {
        return $this->http->delete(self::NAMESPACE . "/stages/{$stageId}");
    }

    /**
     * Get a recurring task template by id.
     *
     * @param  int|string  $templateId
     */
    public function getRecurringTemplate($templateId): Response
    {
        return $this->http->get(self::NAMESPACE . "/templates/recurring/{$templateId}");
    }
}
