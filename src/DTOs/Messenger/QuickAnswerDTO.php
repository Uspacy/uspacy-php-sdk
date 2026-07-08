<?php

namespace Uspacy\SDK\DTOs\Messenger;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A messenger quick answer / quick reply (mirrors the JS `IQuickAnswer`).
 */
final class QuickAnswerDTO
{
    use HasRawData;

    /**
     * @param  array<int, mixed>  $availableForUsers
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $name,
        public readonly ?string $message,
        public readonly ?string $status,
        public readonly ?int $ownerId,
        public readonly array $availableForUsers,
        public readonly ?int $createdAt,
        public readonly ?int $updatedAt,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (string) $data['id'] : null,
            name: $data['name'] ?? null,
            message: $data['message'] ?? null,
            status: $data['status'] ?? null,
            ownerId: $data['ownerId'] ?? null,
            availableForUsers: $data['availableForUsers'] ?? [],
            createdAt: $data['createdAt'] ?? null,
            updatedAt: $data['updatedAt'] ?? null,
            raw: $data,
        );
    }
}
