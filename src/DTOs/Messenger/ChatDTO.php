<?php

namespace Uspacy\SDK\DTOs\Messenger;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A messenger chat (mirrors the JS `IChat`).
 *
 * Documented fields are typed; the full payload (including `lastMessage`) is
 * retained in {@see $raw}.
 */
final class ChatDTO
{
    use HasRawData;

    /**
     * @param  array<int, mixed>  $members
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $name,
        public readonly ?string $type,
        public readonly ?int $ownerId,
        public readonly ?int $groupId,
        public readonly ?int $timestamp,
        public readonly ?bool $pinned,
        public readonly array $members,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (string) $data['id'] : null,
            name: $data['name'] ?? null,
            type: $data['type'] ?? null,
            ownerId: $data['ownerId'] ?? null,
            groupId: $data['groupId'] ?? null,
            timestamp: $data['timestamp'] ?? null,
            pinned: $data['pinned'] ?? null,
            members: $data['members'] ?? [],
            raw: $data,
        );
    }
}
