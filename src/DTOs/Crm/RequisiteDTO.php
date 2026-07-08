<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A CRM requisite (card or bank; mirrors the JS `IRequisite`).
 *
 * Documented fields are typed; the full payload is retained in {@see $raw} and
 * reachable via {@see get()} / {@see has()}.
 */
final class RequisiteDTO
{
    use HasRawData;

    /**
     * @param  array<string, mixed>  $fields
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $name,
        public readonly ?bool $isBasic,
        public readonly ?int $templateId,
        public readonly ?int $sealPicture,
        public readonly ?int $signPicture,
        public readonly array $fields,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            isBasic: $data['is_basic'] ?? null,
            templateId: $data['template_id'] ?? null,
            sealPicture: $data['seal_picture'] ?? null,
            signPicture: $data['sign_picture'] ?? null,
            fields: $data['fields'] ?? [],
            raw: $data,
        );
    }
}
