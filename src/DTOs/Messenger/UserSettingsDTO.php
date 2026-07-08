<?php

namespace Uspacy\SDK\DTOs\Messenger;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * Messenger user settings (mirrors the JS `IUserSettings`).
 */
final class UserSettingsDTO
{
    use HasRawData;

    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?string $id,
        public readonly ?int $authUserId,
        public readonly ?bool $isInternalMsgSoundEnabled,
        public readonly ?bool $isExternalMsgSoundEnabled,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (string) $data['id'] : null,
            authUserId: $data['authUserId'] ?? null,
            isInternalMsgSoundEnabled: $data['isInternalMsgSoundEnabled'] ?? null,
            isExternalMsgSoundEnabled: $data['isExternalMsgSoundEnabled'] ?? null,
            raw: $data,
        );
    }
}
