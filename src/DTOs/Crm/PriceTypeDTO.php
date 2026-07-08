<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A CRM product price type (mirrors the JS `IPriceType`).
 */
final class PriceTypeDTO
{
    use HasRawData;

    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $title,
        public readonly ?string $code,
        public readonly ?int $sort,
        public readonly ?bool $isDefault,
        public readonly ?bool $active,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'] ?? null,
            code: $data['code'] ?? null,
            sort: $data['sort'] ?? null,
            isDefault: $data['default'] ?? null,
            active: $data['active'] ?? null,
            raw: $data,
        );
    }
}
