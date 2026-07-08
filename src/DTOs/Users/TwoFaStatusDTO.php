<?php

namespace Uspacy\SDK\DTOs\Users;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * Two-factor authentication status (mirrors the JS `I2FaStatus`).
 */
final class TwoFaStatusDTO
{
    use HasRawData;

    public function __construct(
        public readonly ?bool $enabled,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            enabled: $data['enabled'] ?? null,
            raw: $data,
        );
    }
}
