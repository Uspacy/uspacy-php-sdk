<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A field available to CRM document templates (mirrors the JS
 * `IDocumentTemplateField`).
 */
final class DocumentTemplateFieldDTO
{
    use HasRawData;

    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $name,
        public readonly ?string $entity,
        public readonly ?string $symbolCode,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            entity: $data['entity'] ?? null,
            symbolCode: $data['symbol_code'] ?? null,
            raw: $data,
        );
    }
}
