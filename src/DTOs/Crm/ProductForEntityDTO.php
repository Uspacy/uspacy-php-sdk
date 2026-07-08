<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A line product attached to a CRM entity (mirrors the JS `IProductForEntity`).
 */
final class ProductForEntityDTO
{
    use HasRawData;

    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $title,
        public readonly float|int|null $price,
        public readonly ?string $currency,
        public readonly float|int|null $quantity,
        public readonly ?string $measurementUnitAbbr,
        public readonly float|int|null $discountValue,
        public readonly ?string $discountType,
        public readonly float|int|null $amount,
        public readonly ?int $priceTypeId,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'] ?? null,
            price: $data['price'] ?? null,
            currency: $data['currency'] ?? null,
            quantity: $data['quantity'] ?? null,
            measurementUnitAbbr: $data['measurement_unit_abbr'] ?? null,
            discountValue: $data['discount_value'] ?? null,
            discountType: $data['discount_type'] ?? null,
            amount: $data['amount'] ?? null,
            priceTypeId: $data['price_type_id'] ?? null,
            raw: $data,
        );
    }
}
