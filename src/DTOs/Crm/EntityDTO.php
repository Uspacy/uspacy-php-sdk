<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A CRM entity record (deal, lead, contact, company, product, ...).
 *
 * CRM entities are almost entirely portal-specific custom fields — the API model
 * guarantees only `id`. So only `id` is typed; read every other field (including
 * custom fields) with {@see get()} / {@see has()} or via {@see $raw}, e.g.
 * `$entity->get('title')`, `$entity->get('customfield_1')`.
 */
final class EntityDTO
{
    use HasRawData;

    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            raw: $data,
        );
    }
}
