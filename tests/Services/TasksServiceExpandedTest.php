<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class TasksServiceExpandedTest extends TestCase
{
    public function test_single_task_ops(): void
    {
        $this->sdk->tasks()->getTask(5, ['crm_entity_list' => true]);
        $this->assertRequestSent('GET', '/tasks/v1/tasks/5/', null, ['crm_entity_list' => true]);

        $this->sdk->tasks()->updateTask(5, ['title' => 'X']);
        $this->assertRequestSent('PATCH', '/tasks/v1/tasks/5/', ['title' => 'X']);

        $this->sdk->tasks()->deleteTask(5);
        $this->assertRequestSent('DELETE', '/tasks/v1/tasks/5/');

        $this->sdk->tasks()->replicateTask(5, ['count' => 2]);
        $this->assertRequestSent('POST', '/tasks/v1/tasks/5/replicate', ['count' => 2]);
    }

    public function test_status_and_delegation(): void
    {
        $this->sdk->tasks()->updateTaskStatus(5, 'in_work');
        $this->assertRequestSent('PATCH', '/tasks/v1/tasks/5/in_work');

        $this->sdk->tasks()->delegateTask(5, 42);
        $this->assertRequestSent('PATCH', '/tasks/v1/tasks/5/delegation', ['user_id' => 42]);
    }

    public function test_mass_operations(): void
    {
        $this->sdk->tasks()->massEditingTasks(['1', '2'], [], false, ['priority' => 'high'], ['notify' => true]);
        $this->assertRequestSent('POST', '/tasks/v1/tasks/mass_edit/', [
            'taskIds' => ['1', '2'],
            'exceptIds' => [],
            'all' => false,
            'payload' => ['priority' => 'high'],
            'settings' => ['notify' => true],
        ]);

        $this->sdk->tasks()->massDeletionTasks(['1'], [], false);
        $this->assertRequestSent('POST', '/tasks/v1/tasks/mass_deletion/', ['taskIds' => ['1'], 'exceptIds' => [], 'all' => false]);

        $this->sdk->tasks()->massCompletionTasks([], [], true, ['groupId' => 3]);
        $this->assertRequestSent('POST', '/tasks/v1/tasks/mass_ready/', ['taskIds' => [], 'exceptIds' => [], 'all' => true], ['groupId' => 3]);
    }

    public function test_templates_and_hierarchy(): void
    {
        $this->sdk->tasks()->getRecurringTemplates(['page' => 1]);
        $this->assertRequestSent('GET', '/tasks/v1/templates/recurring', null, ['page' => 1]);

        $this->sdk->tasks()->getOneTimeTemplates();
        $this->assertRequestSent('GET', '/tasks/v1/templates/one_time');

        $this->sdk->tasks()->createTemplate('recurring', ['title' => 'T']);
        $this->assertRequestSent('POST', '/tasks/v1/templates/recurring', ['title' => 'T']);

        $this->sdk->tasks()->getHierarchies();
        $this->assertRequestSent('GET', '/tasks/v1/tasks/hierarchy');
    }

    public function test_fields(): void
    {
        $this->sdk->tasks()->getTasksFields();
        $this->assertRequestSent('GET', '/tasks/v1/tasks/fields');

        $this->sdk->tasks()->updateTasksListValues('code1', [['value' => 'a']]);
        $this->assertRequestSent('POST', '/tasks/v1/tasks/fields/lists/code1', [['value' => 'a']]);

        $this->sdk->tasks()->deleteTasksListValues('code1', 'v1');
        $this->assertRequestSent('DELETE', '/tasks/v1/tasks/fields/lists/code1/v1');
    }

    public function test_transfers(): void
    {
        $this->sdk->tasks()->transferTasksToUser(['from' => 1, 'to' => 2]);
        $this->assertRequestSent('POST', '/tasks/v1/transfers/user', ['from' => 1, 'to' => 2]);

        $this->sdk->tasks()->getTransferTasksProgress();
        $this->assertRequestSent('GET', '/tasks/v1/transfers/progress');
    }

    public function test_trash(): void
    {
        $this->sdk->tasks()->getTrashTasks(['page' => 1]);
        $this->assertRequestSent('GET', '/tasks/v1/trash/tasks', null, ['page' => 1]);

        $this->sdk->tasks()->restoreTrashTasks([1, 2], [], false, ['groupId' => 3]);
        $this->assertRequestSent('PATCH', '/tasks/v1/trash/tasks/restore', ['id' => [1, 2], 'all' => false, 'except_ids' => []], ['groupId' => 3]);

        $this->sdk->tasks()->deleteTrashTasks([1], [], false);
        $this->assertRequestSent('DELETE', '/tasks/v1/trash/tasks', ['id' => [1], 'all' => false, 'except_ids' => []]);
    }

    public function test_checklists(): void
    {
        $this->sdk->tasks()->createChecklist(5, ['name' => 'C']);
        $this->assertRequestSent('POST', '/tasks/v1/tasks/5/checklists/', ['name' => 'C']);

        $this->sdk->tasks()->createChecklistItem(9, ['text' => 'do']);
        $this->assertRequestSent('POST', '/tasks/v1/tasks/checklists/9/items', ['text' => 'do']);

        $this->sdk->tasks()->deleteChecklistItem(9, 3);
        $this->assertRequestSent('DELETE', '/tasks/v1/tasks/checklists/9/items/3');
    }
}
