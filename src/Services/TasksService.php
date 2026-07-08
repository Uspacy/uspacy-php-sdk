<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\DTOs\Collection;
use Uspacy\SDK\DTOs\Crm\FieldDTO;
use Uspacy\SDK\DTOs\Tasks\TaskDTO;

/**
 * Tasks service.
 *
 * Covers the `tasks/v1` module: tasks CRUD, kanban stages, custom fields,
 * templates, transfers, trash and checklists. Mirrors the Go SDK's `tasks.go`
 * and the JS SDK's TasksService. Task-shaped responses return {@see TaskDTO};
 * task fields reuse {@see FieldDTO} (the shared `IField` model).
 */
class TasksService extends Service
{
    private const NAMESPACE = '/tasks/v1';

    private const TASKS = '/tasks/v1/tasks';

    private const TEMPLATES = '/tasks/v1/templates';

    private const TRANSFERS = '/tasks/v1/transfers';

    private const TRASH = '/tasks/v1/trash/tasks';

    /**
     * Get a page of tasks.
     *
     * @param  array  $params  query parameters (page, list, filters, ...)
     * @return Collection<TaskDTO>
     */
    public function getTasks(array $params = []): Collection
    {
        return $this->toTaskCollection($this->http->get(self::NAMESPACE . '/tasks', $params));
    }

    /**
     * Create a task.
     */
    public function createTask(array $data): TaskDTO
    {
        return $this->toTask($this->http->post(self::NAMESPACE . '/tasks', $data));
    }

    /**
     * Update a task.
     *
     * @param  int|string  $taskId
     */
    public function patchTask($taskId, array $data): TaskDTO
    {
        return $this->toTask($this->http->patch(self::NAMESPACE . "/tasks/{$taskId}", $data));
    }

    /**
     * Mark a task as ready (completed).
     *
     * @param  int|string  $taskId
     */
    public function markTaskReady($taskId): TaskDTO
    {
        return $this->toTask($this->http->patch(self::NAMESPACE . "/tasks/{$taskId}/ready"));
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
     *
     * @return array<int, FieldDTO>
     */
    public function getTaskFields(): array
    {
        return $this->toFields($this->http->get(self::NAMESPACE . '/custom_fields/tasks/fields'));
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
    public function getRecurringTemplate($templateId): TaskDTO
    {
        return $this->toTask($this->http->get(self::NAMESPACE . "/templates/recurring/{$templateId}"));
    }

    /**
     * Get a single task by id.
     *
     * @param  int|string  $id
     * @param  array  $params  extra query params (e.g. crm_entity_list)
     */
    public function getTask($id, array $params = []): TaskDTO
    {
        return $this->toTask($this->http->get(self::TASKS . "/{$id}/", $params));
    }

    /**
     * Update a task (with trailing slash, matching the JS SDK).
     *
     * @param  int|string  $id
     */
    public function updateTask($id, array $data): TaskDTO
    {
        return $this->toTask($this->http->patch(self::TASKS . "/{$id}/", $data));
    }

    /**
     * Delete a task.
     *
     * @param  int|string  $id
     */
    public function deleteTask($id): Response
    {
        return $this->http->delete(self::TASKS . "/{$id}/");
    }

    /**
     * Replicate (duplicate) a task.
     *
     * @param  int|string  $id
     */
    public function replicateTask($id, array $data): TaskDTO
    {
        return $this->toTask($this->http->post(self::TASKS . "/{$id}/replicate", $data));
    }

    /**
     * Change a task status via an action verb (e.g. ready, in_work, deferred).
     *
     * @param  int|string  $id
     */
    public function updateTaskStatus($id, string $action): TaskDTO
    {
        return $this->toTask($this->http->patch(self::TASKS . "/{$id}/{$action}"));
    }

    /**
     * Delegate a task to a user.
     *
     * @param  int|string  $id
     * @param  int|string  $userId
     */
    public function delegateTask($id, $userId): TaskDTO
    {
        return $this->toTask($this->http->patch(self::TASKS . "/{$id}/delegation", ['user_id' => $userId]));
    }

    /**
     * Mass edit tasks.
     *
     * @param  array  $taskIds  task ids to edit
     * @param  array  $exceptIds  ids to exclude when $all is true
     * @param  bool  $all  edit every task matching $params
     * @param  array  $payload  fields to apply
     * @param  array  $settings  editing settings
     * @param  array  $params  query filters used when $all is true
     */
    public function massEditingTasks(array $taskIds = [], array $exceptIds = [], bool $all = false, array $payload = [], array $settings = [], array $params = []): Response
    {
        return $this->http->post(self::TASKS . '/mass_edit/', [
            'taskIds' => $taskIds,
            'exceptIds' => $exceptIds,
            'all' => $all,
            'payload' => $payload,
            'settings' => $settings,
        ], $params);
    }

    /**
     * Mass delete tasks.
     *
     * @param  array  $params  query filters used when $all is true
     */
    public function massDeletionTasks(array $taskIds = [], array $exceptIds = [], bool $all = false, array $params = []): Response
    {
        return $this->http->post(self::TASKS . '/mass_deletion/', [
            'taskIds' => $taskIds,
            'exceptIds' => $exceptIds,
            'all' => $all,
        ], $params);
    }

    /**
     * Mass complete tasks.
     *
     * @param  array  $params  query filters used when $all is true
     */
    public function massCompletionTasks(array $taskIds = [], array $exceptIds = [], bool $all = false, array $params = []): Response
    {
        return $this->http->post(self::TASKS . '/mass_ready/', [
            'taskIds' => $taskIds,
            'exceptIds' => $exceptIds,
            'all' => $all,
        ], $params);
    }

    /**
     * Get recurring task templates.
     *
     * @return Collection<TaskDTO>
     */
    public function getRecurringTemplates(array $params = []): Collection
    {
        return $this->toTaskCollection($this->http->get(self::TEMPLATES . '/recurring', $params));
    }

    /**
     * Get one-time task templates.
     *
     * @return Collection<TaskDTO>
     */
    public function getOneTimeTemplates(array $params = []): Collection
    {
        return $this->toTaskCollection($this->http->get(self::TEMPLATES . '/one_time', $params));
    }

    /**
     * Create a task template of the given type (recurring / one_time).
     */
    public function createTemplate(string $type, array $data): TaskDTO
    {
        return $this->toTask($this->http->post(self::TEMPLATES . "/{$type}", $data));
    }

    /**
     * Get the task hierarchy.
     *
     * @return Collection<TaskDTO>
     */
    public function getHierarchies(array $params = []): Collection
    {
        return $this->toTaskCollection($this->http->get(self::TASKS . '/hierarchy', $params));
    }

    /**
     * Get task fields (JS-parity endpoint at `tasks/fields`).
     *
     * @return array<int, FieldDTO>
     */
    public function getTasksFields(): array
    {
        return $this->toFields($this->http->get(self::TASKS . '/fields'));
    }

    /**
     * Create a task field.
     */
    public function createTasksField(array $data): FieldDTO
    {
        return FieldDTO::fromArray($this->http->post(self::TASKS . '/fields', $data)->json() ?? []);
    }

    /**
     * Update a task field by code.
     */
    public function updateTasksField(string $fieldCode, array $data): FieldDTO
    {
        return FieldDTO::fromArray($this->http->patch(self::TASKS . "/fields/{$fieldCode}", $data)->json() ?? []);
    }

    /**
     * Delete a task field by code.
     */
    public function deleteTasksField(string $fieldCode): Response
    {
        return $this->http->delete(self::TASKS . "/fields/{$fieldCode}");
    }

    /**
     * Add/replace list values for a task field.
     */
    public function updateTasksListValues(string $fieldCode, array $values): Response
    {
        return $this->http->post(self::TASKS . "/fields/lists/{$fieldCode}", $values);
    }

    /**
     * Delete a single list value from a task field.
     */
    public function deleteTasksListValues(string $fieldCode, string $value): Response
    {
        return $this->http->delete(self::TASKS . "/fields/lists/{$fieldCode}/{$value}");
    }

    /**
     * Transfer tasks to another user.
     */
    public function transferTasksToUser(array $data): Response
    {
        return $this->http->post(self::TRANSFERS . '/user', $data);
    }

    /**
     * Get the number of tasks that would be transferred.
     */
    public function getTransferTasksQuantity(array $data): Response
    {
        return $this->http->post(self::TRANSFERS . '/quantity', $data);
    }

    /**
     * Get the progress of a running task transfer.
     */
    public function getTransferTasksProgress(): Response
    {
        return $this->http->get(self::TRANSFERS . '/progress');
    }

    /**
     * Stop a running task transfer.
     */
    public function stopTransferTasks(): Response
    {
        return $this->http->get(self::TRANSFERS . '/stop');
    }

    /**
     * Get deleted (trash) tasks.
     *
     * @return Collection<TaskDTO>
     */
    public function getTrashTasks(array $params = []): Collection
    {
        return $this->toTaskCollection($this->http->get(self::TRASH, $params));
    }

    /**
     * Get a single deleted (trash) task by id.
     *
     * @param  int|string  $id
     */
    public function getTrashTask($id): TaskDTO
    {
        return $this->toTask($this->http->get(self::TRASH, ['id' => $id]));
    }

    /**
     * Restore tasks from the trash.
     *
     * @param  array  $itemIds  ids to restore
     * @param  array  $exceptIds  ids to exclude when $all is true
     * @param  bool  $all  restore every task matching $filterParams
     * @param  array  $filterParams  query filters used when $all is true
     */
    public function restoreTrashTasks(array $itemIds = [], array $exceptIds = [], bool $all = false, array $filterParams = []): Response
    {
        return $this->http->patch(self::TRASH . '/restore', [
            'id' => $itemIds,
            'all' => $all,
            'except_ids' => $exceptIds,
        ], $filterParams);
    }

    /**
     * Permanently delete tasks from the trash.
     *
     * @param  array  $itemIds  ids to delete
     * @param  array  $exceptIds  ids to exclude when $all is true
     * @param  bool  $all  delete every task matching $filterParams
     * @param  array  $filterParams  query filters used when $all is true
     */
    public function deleteTrashTasks(array $itemIds = [], array $exceptIds = [], bool $all = false, array $filterParams = []): Response
    {
        return $this->http->delete(self::TRASH, [
            'id' => $itemIds,
            'all' => $all,
            'except_ids' => $exceptIds,
        ], $filterParams);
    }

    /**
     * Create a checklist on a task. Returns the updated task.
     *
     * @param  int|string  $taskId
     */
    public function createChecklist($taskId, array $data): TaskDTO
    {
        return $this->toTask($this->http->post(self::TASKS . "/{$taskId}/checklists/", $data));
    }

    /**
     * Update a checklist. Returns the updated task.
     *
     * @param  int|string  $id
     */
    public function updateChecklist($id, array $data): TaskDTO
    {
        return $this->toTask($this->http->patch(self::TASKS . "/checklists/{$id}", $data));
    }

    /**
     * Delete a checklist.
     *
     * @param  int|string  $id
     */
    public function deleteChecklist($id): Response
    {
        return $this->http->delete(self::TASKS . "/checklists/{$id}");
    }

    /**
     * Create a checklist item. Returns the updated task.
     *
     * @param  int|string  $id  checklist id
     */
    public function createChecklistItem($id, array $data): TaskDTO
    {
        return $this->toTask($this->http->post(self::TASKS . "/checklists/{$id}/items", $data));
    }

    /**
     * Update a checklist item. Returns the updated task.
     *
     * @param  int|string  $id  checklist id
     * @param  int|string  $itemId
     */
    public function updateChecklistItem($id, $itemId, array $data): TaskDTO
    {
        return $this->toTask($this->http->patch(self::TASKS . "/checklists/{$id}/items/{$itemId}", $data));
    }

    /**
     * Delete a checklist item.
     *
     * @param  int|string  $id  checklist id
     * @param  int|string  $itemId
     */
    public function deleteChecklistItem($id, $itemId): Response
    {
        return $this->http->delete(self::TASKS . "/checklists/{$id}/items/{$itemId}");
    }

    private function toTask(Response $response): TaskDTO
    {
        return TaskDTO::fromArray($response->json() ?? []);
    }

    /**
     * @return Collection<TaskDTO>
     */
    private function toTaskCollection(Response $response): Collection
    {
        return Collection::fromArray($response->json() ?? [], [TaskDTO::class, 'fromArray']);
    }

    /**
     * @return array<int, FieldDTO>
     */
    private function toFields(Response $response): array
    {
        $data = $response->json() ?? [];

        return array_map([FieldDTO::class, 'fromArray'], $data['data'] ?? []);
    }
}
