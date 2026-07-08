<?php

namespace Uspacy\SDK\DTOs;

/**
 * Pagination metadata returned alongside list responses (the `meta` envelope).
 *
 * Known fields are typed; the full payload is retained in {@see $raw}.
 */
final class Meta
{
    public function __construct(
        public readonly ?int $total,
        public readonly ?int $page,
        public readonly ?int $list,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            total: $data['total'] ?? null,
            page: $data['page'] ?? null,
            list: $data['list'] ?? null,
            raw: $data,
        );
    }
}
