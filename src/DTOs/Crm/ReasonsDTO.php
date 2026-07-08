<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * Grouped kanban stage reasons (mirrors the JS `IReasons`: `{ SUCCESS, FAIL }`).
 */
final class ReasonsDTO
{
    use HasRawData;

    /**
     * @param  array<int, ReasonDTO>  $success
     * @param  array<int, ReasonDTO>  $fail
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly array $success,
        public readonly array $fail,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            success: array_map([ReasonDTO::class, 'fromArray'], $data['SUCCESS'] ?? []),
            fail: array_map([ReasonDTO::class, 'fromArray'], $data['FAIL'] ?? []),
            raw: $data,
        );
    }
}
