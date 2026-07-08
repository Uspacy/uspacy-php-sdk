<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A CRM product (mirrors the JS `IProduct`).
 *
 * Common fields are typed; products can also carry custom fields, so the full
 * payload is retained in {@see $raw} and reachable via {@see get()} / {@see has()}.
 */
final class ProductDTO
{
    use HasRawData;

    /**
     * @param  array<int, mixed>  $prices
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $title,
        public readonly ?string $type,
        public readonly ?string $article,
        public readonly ?bool $isActive,
        public readonly ?string $availability,
        public readonly ?int $productCategoryId,
        public readonly ?int $measurementUnitId,
        public readonly float|int|null $quantity,
        public readonly array $prices,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'] ?? null,
            type: $data['type'] ?? null,
            article: $data['article'] ?? null,
            isActive: $data['is_active'] ?? null,
            availability: $data['availability'] ?? null,
            productCategoryId: $data['product_category_id'] ?? null,
            measurementUnitId: $data['measurement_unit_id'] ?? null,
            quantity: $data['quantity'] ?? null,
            prices: $data['prices'] ?? [],
            raw: $data,
        );
    }
}
