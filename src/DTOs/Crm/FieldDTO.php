<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A CRM field definition (mirrors the JS `IField`).
 *
 * Documented fields are typed; the full payload is retained in {@see $raw} and
 * reachable via {@see get()} / {@see has()}.
 */
final class FieldDTO
{
    use HasRawData;

    /**
     * @param  array<int, mixed>  $values
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $code,
        public readonly ?string $type,
        public readonly ?bool $required,
        public readonly ?bool $editable,
        public readonly ?bool $show,
        public readonly ?bool $hidden,
        public readonly ?bool $multiple,
        public readonly ?bool $systemField,
        public readonly array $values,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            code: $data['code'] ?? null,
            type: $data['type'] ?? null,
            required: $data['required'] ?? null,
            editable: $data['editable'] ?? null,
            show: $data['show'] ?? null,
            hidden: $data['hidden'] ?? null,
            multiple: $data['multiple'] ?? null,
            systemField: $data['system_field'] ?? null,
            values: $data['values'] ?? [],
            raw: $data,
        );
    }
}
