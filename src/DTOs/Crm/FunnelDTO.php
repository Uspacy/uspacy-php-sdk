<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A CRM funnel (mirrors the JS `IFunnel`).
 *
 * Documented fields are typed; the full payload is retained in {@see $raw}.
 */
final class FunnelDTO
{
    use HasRawData;

    /**
     * @param  array<int, StageDTO>  $stages
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $title,
        public readonly ?string $funnelCode,
        public readonly ?bool $isDefault,
        public readonly ?bool $active,
        public readonly ?bool $tariffLimited,
        public readonly array $stages,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'] ?? null,
            funnelCode: $data['funnel_code'] ?? null,
            isDefault: $data['default'] ?? null,
            active: $data['active'] ?? null,
            tariffLimited: $data['tariff_limited'] ?? null,
            stages: array_map([StageDTO::class, 'fromArray'], $data['stages'] ?? []),
            raw: $data,
        );
    }
}
