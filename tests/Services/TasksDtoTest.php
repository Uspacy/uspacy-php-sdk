<?php

namespace Uspacy\SDK\Tests\Services;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Uspacy\SDK\DTOs\Collection;
use Uspacy\SDK\DTOs\Crm\FieldDTO;
use Uspacy\SDK\DTOs\Tasks\TaskDTO;
use Uspacy\SDK\Http\Client\Requests\GetRequest;
use Uspacy\SDK\Http\Client\Requests\PatchRequest;
use Uspacy\SDK\Http\Client\Requests\PostRequest;
use Uspacy\SDK\Tests\TestCase;

class TasksDtoTest extends TestCase
{
    public function test_get_tasks_hydrates_collection(): void
    {
        $this->mockGet([
            'data' => [
                ['id' => '10', 'title' => 'Ship', 'status' => 'in_work', 'priority' => 'high', 'customfield_1' => 'x'],
            ],
            'meta' => ['total' => 1, 'page' => 1],
        ]);

        $result = $this->sdk->tasks()->getTasks(['page' => 1]);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertInstanceOf(TaskDTO::class, $result->data[0]);
        $this->assertSame('10', $result->data[0]->id);
        $this->assertSame('in_work', $result->data[0]->status);
        $this->assertSame('x', $result->data[0]->get('customfield_1'));
        $this->assertSame(1, $result->meta->total);
    }

    public function test_id_is_normalised_to_string(): void
    {
        // API may return a numeric id; the DTO exposes it as string.
        $this->mockGet(['id' => 42, 'title' => 'T', 'responsibleId' => 7, 'groupId' => 3]);

        $task = $this->sdk->tasks()->getTask(42);

        $this->assertSame('42', $task->id);
        $this->assertSame('7', $task->responsibleId);
        $this->assertSame('3', $task->groupId);
    }

    public function test_status_and_delegation_return_task_dto(): void
    {
        $this->sdk->withMockClient(new MockClient([
            PatchRequest::class => MockResponse::make(['id' => '5', 'status' => 'in_work'], 200),
        ]));

        $this->assertInstanceOf(TaskDTO::class, $this->sdk->tasks()->updateTaskStatus(5, 'in_work'));
        $this->assertInstanceOf(TaskDTO::class, $this->sdk->tasks()->delegateTask(5, 7));
        $this->assertInstanceOf(TaskDTO::class, $this->sdk->tasks()->markTaskReady(5));
    }

    public function test_checklist_ops_return_the_task(): void
    {
        $this->sdk->withMockClient(new MockClient([
            PostRequest::class => MockResponse::make(['id' => '5', 'title' => 'T'], 201),
            PatchRequest::class => MockResponse::make(['id' => '5', 'title' => 'T'], 200),
        ]));

        $this->assertInstanceOf(TaskDTO::class, $this->sdk->tasks()->createChecklist(5, ['name' => 'C']));
        $this->assertInstanceOf(TaskDTO::class, $this->sdk->tasks()->createChecklistItem(9, ['text' => 'do']));
        $this->assertInstanceOf(TaskDTO::class, $this->sdk->tasks()->updateChecklistItem(9, 3, ['done' => true]));
    }

    public function test_task_fields_hydrate_field_dtos_from_data_envelope(): void
    {
        $this->mockGet(['data' => [['name' => 'Priority', 'code' => 'priority', 'type' => 'list']]]);

        $fields = $this->sdk->tasks()->getTasksFields();

        $this->assertInstanceOf(FieldDTO::class, $fields[0]);
        $this->assertSame('priority', $fields[0]->code);
    }

    public function test_empty_body_does_not_throw(): void
    {
        $this->sdk->withMockClient(new MockClient([
            GetRequest::class => MockResponse::make('', 204),
        ]));

        $this->assertInstanceOf(Collection::class, $this->sdk->tasks()->getTasks());
        $this->assertInstanceOf(TaskDTO::class, $this->sdk->tasks()->getTask(1));
        $this->assertSame([], $this->sdk->tasks()->getTasksFields());
    }

    private function mockGet(array $payload): void
    {
        $this->sdk->withMockClient(new MockClient([
            GetRequest::class => MockResponse::make($payload, 200),
        ]));
    }
}
