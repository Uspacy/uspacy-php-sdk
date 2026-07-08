<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A CRM product tax (mirrors the JS `ITax`).
 */
final class TaxDTO
{
    use HasRawData;

    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $name,
        public readonly float|int|null $rate,
        public readonly int|bool|null $isActive,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            rate: $data['rate'] ?? null,
            isActive: $data['is_active'] ?? null,
            raw: $data,
        );
    }
}
