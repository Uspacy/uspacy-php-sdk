<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A CRM kanban stage fail/success reason (mirrors the JS `IReason`).
 */
final class ReasonDTO
{
    use HasRawData;

    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $title,
        public readonly ?int $sort,
        public readonly ?int $funnelId,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'] ?? null,
            sort: $data['sort'] ?? null,
            funnelId: $data['funnel_id'] ?? null,
            raw: $data,
        );
    }
}
