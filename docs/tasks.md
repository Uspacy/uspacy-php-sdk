# Tasks service

`$sdk->tasks()` covers the `tasks/v1` module. Task-shaped responses return a
typed `TaskDTO` (namespace `Uspacy\SDK\DTOs\Tasks`); task fields reuse the shared
`FieldDTO`. Every output DTO keeps the full `raw` payload, so **custom fields are
never lost**.

> `TaskDTO::id` is a **string** (task ids are strings in the API); numeric ids in
> responses are normalised to string.

```php
use Uspacy\SDK\Http\Client\UspacySDK;

$sdk = new UspacySDK('https://acme.uspacy.ua', $accessToken);
```

## Reading tasks

```php
// List -> Collection<TaskDTO>
$page = $sdk->tasks()->getTasks(['page' => 1, 'list' => 20]);
foreach ($page->data as $task) {
    $task->id;                   // string
    $task->title;
    $task->status;
    $task->responsibleId;
    $task->get('customfield_1'); // custom field (or null)
}
$page->meta->total;

// Single -> TaskDTO
$task = $sdk->tasks()->getTask(15, ['crm_entity_list' => true]);

// Templates & hierarchy -> Collection<TaskDTO>
$sdk->tasks()->getRecurringTemplates();
$sdk->tasks()->getOneTimeTemplates();
$sdk->tasks()->getHierarchies();
$sdk->tasks()->getTrashTasks();
```

## Writing tasks

```php
// Create / update / status / delegate -> TaskDTO
$sdk->tasks()->createTask(['title' => 'Ship SDK', 'responsibleId' => 7]);
$sdk->tasks()->patchTask(15, ['title' => 'Ship v1']);
$sdk->tasks()->updateTask(15, ['title' => 'Ship v1']);
$sdk->tasks()->updateTaskStatus(15, 'in_work');
$sdk->tasks()->delegateTask(15, 42);
$sdk->tasks()->markTaskReady(15);
$sdk->tasks()->replicateTask(15, ['count' => 2]);
$sdk->tasks()->createTemplate('recurring', ['title' => 'Weekly report']);

// Checklists return the updated task -> TaskDTO
$sdk->tasks()->createChecklist(15, ['name' => 'Launch']);
$sdk->tasks()->createChecklistItem(9, ['text' => 'Write tests']);
$sdk->tasks()->updateChecklistItem(9, 3, ['done' => true]);

// Deletes, mass ops, transfers and stages return the raw Response
$sdk->tasks()->deleteTask(15);
$sdk->tasks()->massDeletionTasks(taskIds: ['15', '16']);
$sdk->tasks()->transferTasksToUser(['from_user_id' => 1, 'to_user_id' => 2]);
$sdk->tasks()->getKanbanStages(['groupId' => 3]);
```

## Fields

```php
$sdk->tasks()->getTaskFields();   // FieldDTO[] (custom_fields endpoint)
$sdk->tasks()->getTasksFields();  // FieldDTO[] (tasks/fields endpoint)

$sdk->tasks()->createTasksField(['name' => 'Priority', 'code' => 'priority']); // FieldDTO
$sdk->tasks()->updateTasksField('priority', ['name' => 'Priority level']);     // FieldDTO
$sdk->tasks()->deleteTasksField('priority');                                   // raw Response
```

## Return-type reference

| Method | Returns |
| --- | --- |
| `getTasks`, `getRecurringTemplates`, `getOneTimeTemplates`, `getHierarchies`, `getTrashTasks` | `Collection<TaskDTO>` |
| `getTask`, `getTrashTask`, `getRecurringTemplate`, `createTask`, `patchTask`, `updateTask`, `markTaskReady`, `replicateTask`, `updateTaskStatus`, `delegateTask`, `createTemplate`, `createChecklist`, `updateChecklist`, `createChecklistItem`, `updateChecklistItem` | `TaskDTO` |
| `getTaskFields`, `getTasksFields` | `FieldDTO[]` |
| `createTasksField`, `updateTasksField` | `FieldDTO` |
| `deleteTask`, mass ops, transfers, kanban stages, trash restore/delete, checklist/field deletes, list-value ops | `Saloon\Http\Response` |
