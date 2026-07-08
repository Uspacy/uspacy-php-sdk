<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A CRM document template (mirrors the JS `IDocumentTemplate`).
 */
final class DocumentTemplateDTO
{
    use HasRawData;

    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $name,
        public readonly ?bool $isActive,
        public readonly ?string $code,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            isActive: $data['is_active'] ?? null,
            code: $data['code'] ?? null,
            raw: $data,
        );
    }
}
