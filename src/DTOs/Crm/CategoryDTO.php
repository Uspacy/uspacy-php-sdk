<?php

namespace Uspacy\SDK\DTOs\Crm;

use Uspacy\SDK\DTOs\Concerns\HasRawData;

/**
 * A CRM product category (mirrors the JS `IProductCategory`).
 *
 * Nested `child_categories` are hydrated recursively.
 */
final class CategoryDTO
{
    use HasRawData;

    /**
     * @param  array<int, CategoryDTO>  $childCategories
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $parentId,
        public readonly ?string $name,
        public readonly ?int $sort,
        public readonly int|bool|null $isActive,
        public readonly array $childCategories,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            parentId: $data['parent_id'] ?? null,
            name: $data['name'] ?? null,
            sort: $data['sort'] ?? null,
            isActive: $data['is_active'] ?? null,
            childCategories: array_map([self::class, 'fromArray'], $data['child_categories'] ?? []),
            raw: $data,
        );
    }
}
