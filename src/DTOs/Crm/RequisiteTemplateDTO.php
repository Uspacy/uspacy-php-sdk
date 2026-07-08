<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A CRM requisite template (mirrors the JS `ITemplate`).
 */
final class RequisiteTemplateDTO
{
    use HasRawData;

    /**
     * @param  array<string, mixed>|null  $region
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $name,
        public readonly ?array $region,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            region: $data['region'] ?? null,
            raw: $data,
        );
    }
}
