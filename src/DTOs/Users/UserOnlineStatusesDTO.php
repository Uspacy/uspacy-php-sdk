<?php

namespace Uspacy\SDK\DTOs\Users;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * Online statuses keyed by user id (mirrors the JS `IUserOnlineStatuses` map).
 */
final class UserOnlineStatusesDTO
{
    use HasRawData;

    /**
     * @param  array<string, OnlineStatusDTO>  $statuses
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly array $statuses,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $statuses = [];

        foreach ($data as $userId => $status) {
            if (\is_array($status)) {
                $statuses[$userId] = OnlineStatusDTO::fromArray($status);
            }
        }

        return new self(
            statuses: $statuses,
            raw: $data,
        );
    }
}
