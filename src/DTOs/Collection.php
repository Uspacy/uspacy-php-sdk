<?php

namespace Uspacy\SDK\DTOs;

/**
 * Generic paginated collection: a list of hydrated DTOs plus its {@see Meta}.
 *
 * Mirrors the JS SDK's `IResponseWithMeta<D>` envelope (`{ data, meta }`).
 *
 * @template T
 */
final class Collection
{
    /**
     * @param  array<int, T>  $data  hydrated items
     */
    public function __construct(
        public readonly array $data,
        public readonly Meta $meta,
    ) {
    }

    /**
     * Build a collection from a `{ data, meta }` payload, hydrating each item
     * with the given factory.
     *
     * @param  callable(array): T  $itemFactory
     */
    public static function fromArray(array $payload, callable $itemFactory): self
    {
        return new self(
            data: array_map($itemFactory, $payload['data'] ?? []),
            meta: Meta::fromArray($payload['meta'] ?? []),
        );
    }
}
