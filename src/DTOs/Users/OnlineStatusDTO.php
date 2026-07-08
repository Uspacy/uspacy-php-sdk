<?php

namespace Uspacy\SDK\DTOs\Users;

/**
 * A single user's online status (mirrors the JS `IOnlineStatus`).
 */
final class OnlineStatusDTO
{
    public function __construct(
        public readonly ?bool $isOnline,
        public readonly ?int $lastSeenAt,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isOnline: $data['isOnline'] ?? null,
            lastSeenAt: $data['lastSeenAt'] ?? null,
        );
    }
}
