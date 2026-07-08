<?php

namespace Uspacy\SDK\DTOs\Tasks;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A task (mirrors the JS `ITask`).
 *
 * Common fields are typed; tasks also carry custom fields, so the full payload
 * is retained in {@see $raw} and reachable via {@see get()} / {@see has()}.
 * Note `id` is a string.
 */
final class TaskDTO
{
    use HasRawData;

    /**
     * @param  array<int, mixed>  $accomplicesIds
     * @param  array<int, mixed>  $auditorsIds
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $title,
        public readonly ?string $status,
        public readonly ?string $priority,
        public readonly ?string $responsibleId,
        public readonly ?string $taskType,
        public readonly ?string $body,
        public readonly ?string $groupId,
        public readonly ?string $kanbanStageId,
        public readonly int|string|null $parentId,
        public readonly ?int $deadline,
        public readonly array $accomplicesIds,
        public readonly array $auditorsIds,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (string) $data['id'] : null,
            title: $data['title'] ?? null,
            status: $data['status'] ?? null,
            priority: $data['priority'] ?? null,
            responsibleId: isset($data['responsibleId']) ? (string) $data['responsibleId'] : null,
            taskType: $data['taskType'] ?? null,
            body: $data['body'] ?? null,
            groupId: isset($data['groupId']) ? (string) $data['groupId'] : null,
            kanbanStageId: isset($data['kanbanStageId']) ? (string) $data['kanbanStageId'] : null,
            parentId: $data['parentId'] ?? null,
            deadline: $data['deadline'] ?? null,
            accomplicesIds: $data['accomplicesIds'] ?? [],
            auditorsIds: $data['auditorsIds'] ?? [],
            raw: $data,
        );
    }
}
