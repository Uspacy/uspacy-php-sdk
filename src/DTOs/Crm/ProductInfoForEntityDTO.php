<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * Aggregated line-product info for a CRM entity (mirrors the JS
 * `IProductInfoForEntity`). Nested `list_products` are hydrated.
 */
final class ProductInfoForEntityDTO
{
    use HasRawData;

    /**
     * @param  array<int, ProductForEntityDTO>  $listProducts
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $entityType,
        public readonly ?int $entityId,
        public readonly float|int|null $amountTotal,
        public readonly float|int|null $amountBeforeTax,
        public readonly float|int|null $amountTax,
        public readonly array $listProducts,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            entityType: $data['entity_type'] ?? null,
            entityId: $data['entity_id'] ?? null,
            amountTotal: $data['amount_total'] ?? null,
            amountBeforeTax: $data['amount_before_tax'] ?? null,
            amountTax: $data['amount_tax'] ?? null,
            listProducts: array_map([ProductForEntityDTO::class, 'fromArray'], $data['list_products'] ?? []),
            raw: $data,
        );
    }
}
