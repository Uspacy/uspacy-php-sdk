<?php

namespace Uspacy\SDK\DTOs\Concerns;

/**
 * Convenience access to fields kept in a DTO's raw payload.
 *
 * Lets callers read fields that aren't modeled as typed properties — most
 * importantly portal-specific custom fields (e.g. customfield_1) — without
 * reaching into the `raw` array directly.
 *
 * @property-read array<string, mixed> $raw
 */
trait HasRawData
{
    /**
     * Get any field from the raw payload (typed or custom), with a fallback.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->raw[$key] ?? $default;
    }

    /**
     * Whether the raw payload contains the given field.
     */
    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->raw);
    }
}
