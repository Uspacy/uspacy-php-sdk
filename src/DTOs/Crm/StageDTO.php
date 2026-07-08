<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A CRM kanban stage (mirrors the JS `IStage`).
 *
 * Documented fields are typed; the full payload is retained in {@see $raw}.
 */
final class StageDTO
{
    use HasRawData;

    /**
     * @param  array<int, ReasonDTO>  $reasons
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $title,
        public readonly ?string $stageCode,
        public readonly ?string $color,
        public readonly ?int $sort,
        public readonly ?bool $systemStage,
        public readonly ?int $funnelId,
        public readonly array $reasons,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'] ?? null,
            stageCode: $data['stage_code'] ?? null,
            color: $data['color'] ?? null,
            sort: $data['sort'] ?? null,
            systemStage: $data['system_stage'] ?? null,
            funnelId: $data['funnel_id'] ?? null,
            reasons: array_map([ReasonDTO::class, 'fromArray'], $data['reasons'] ?? []),
            raw: $data,
        );
    }
}
