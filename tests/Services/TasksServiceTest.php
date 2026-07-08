<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class TasksServiceTest extends TestCase
{
    public function test_get_tasks_sends_query(): void
    {
        $this->sdk->tasks()->getTasks(['page' => 1]);

        $this->assertRequestSent('GET', '/tasks/v1/tasks', null, ['page' => 1]);
    }

    public function test_create_task(): void
    {
        $this->sdk->tasks()->createTask(['title' => 'Ship SDK']);

        $this->assertRequestSent('POST', '/tasks/v1/tasks', ['title' => 'Ship SDK']);
    }

    public function test_patch_task(): void
    {
        $this->sdk->tasks()->patchTask(15, ['title' => 'Ship v1']);

        $this->assertRequestSent('PATCH', '/tasks/v1/tasks/15', ['title' => 'Ship v1']);
    }

    public function test_mark_task_ready_is_a_patch_to_ready_subresource(): void
    {
        $this->sdk->tasks()->markTaskReady(15);

        $this->assertRequestSent('PATCH', '/tasks/v1/tasks/15/ready');
    }

    public function test_kanban_stages_with_group_filter(): void
    {
        $this->sdk->tasks()->getKanbanStages(['groupId' => 3]);

        $this->assertRequestSent('GET', '/tasks/v1/stages', null, ['groupId' => 3]);
    }

    public function test_delete_stage(): void
    {
        $this->sdk->tasks()->deleteStage(8);

        $this->assertRequestSent('DELETE', '/tasks/v1/stages/8');
    }

    public function test_task_custom_fields_endpoint(): void
    {
        $this->sdk->tasks()->getTaskFields();

        $this->assertRequestSent('GET', '/tasks/v1/custom_fields/tasks/fields');
    }
}
