<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A CRM product measurement unit (mirrors the JS `IMeasurementUnit`).
 */
final class UnitDTO
{
    use HasRawData;

    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $name,
        public readonly ?string $abbr,
        public readonly int|bool|null $isDefault,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            abbr: $data['abbr'] ?? null,
            isDefault: $data['is_default'] ?? null,
            raw: $data,
        );
    }
}
