<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\DTOs\Crm\ProductForEntityDTO;
use Uspacy\SDK\DTOs\Crm\ProductInfoForEntityDTO;

/**
 * CRM "products for entity" service (line products attached to CRM records).
 *
 * Mirrors the JS SDK's CrmProductsForEntityService: line products live under
 * `/crm/v1/static/list-products`, and their aggregated info under
 * `/crm/v1/static/entity-product-lists`.
 */
class CrmProductsForEntityService extends Service
{
    private const NAMESPACE = '/crm/v1/static/list-products';

    private const INFO_NAMESPACE = '/crm/v1/static/entity-product-lists';

    /**
     * Get aggregated product info attached to an entity.
     *
     * @param  string  $entityType
     * @param  int|string  $entityId
     */
    public function getInfoProductsForEntity(string $entityType, $entityId): ProductInfoForEntityDTO
    {
        return ProductInfoForEntityDTO::fromArray($this->http->get(self::INFO_NAMESPACE, [
            'entity_type' => $entityType,
            'entity_id' => $entityId,
        ])->json() ?? []);
    }

    /**
     * Update aggregated product info.
     *
     * @param  int|string  $id
     */
    public function updateInfoProductForEntity($id, array $info): ProductInfoForEntityDTO
    {
        return ProductInfoForEntityDTO::fromArray($this->http->patch(self::INFO_NAMESPACE . "/{$id}", $info)->json() ?? []);
    }

    /**
     * Get all line products.
     *
     * @return array<int, ProductForEntityDTO>
     */
    public function getProductsForEntity(): array
    {
        $data = $this->http->get(self::NAMESPACE)->json() ?? [];

        return array_map([ProductForEntityDTO::class, 'fromArray'], array_filter($data, 'is_array'));
    }

    /**
     * Get a single line product.
     *
     * @param  int|string  $id
     */
    public function getProductForEntity($id): ProductForEntityDTO
    {
        return ProductForEntityDTO::fromArray($this->http->get(self::NAMESPACE . "/{$id}")->json() ?? []);
    }

    /**
     * Create a line product.
     */
    public function createProductForEntity(array $data): ProductInfoForEntityDTO
    {
        return ProductInfoForEntityDTO::fromArray($this->http->post(self::NAMESPACE, $data)->json() ?? []);
    }

    /**
     * Update a line product.
     *
     * @param  int|string  $id
     */
    public function updateProductForEntity($id, array $data): ProductForEntityDTO
    {
        return ProductForEntityDTO::fromArray($this->http->patch(self::NAMESPACE . "/{$id}", $data)->json() ?? []);
    }

    /**
     * Bulk create line products.
     *
     * @param  array  $listProducts  list of line products
     * @return array<int, ProductForEntityDTO>
     */
    public function createProductsForEntity(array $listProducts): array
    {
        $data = $this->http->post(self::NAMESPACE . '/bulk', ['list_products' => $listProducts])->json() ?? [];

        return array_map([ProductForEntityDTO::class, 'fromArray'], array_filter($data, 'is_array'));
    }

    /**
     * Bulk update line products.
     *
     * @param  array  $listProducts  list of line products
     * @return array<int, ProductForEntityDTO>
     */
    public function updateProductsForEntity(array $listProducts): array
    {
        $data = $this->http->patch(self::NAMESPACE . '/bulk', ['list_products' => $listProducts])->json() ?? [];

        return array_map([ProductForEntityDTO::class, 'fromArray'], array_filter($data, 'is_array'));
    }

    /**
     * Delete a line product.
     *
     * @param  int|string  $id
     */
    public function deleteProductForEntity($id): Response
    {
        return $this->http->delete(self::NAMESPACE . "/{$id}");
    }

    /**
     * Bulk delete line products.
     *
     * @param  array  $ids  line product ids
     */
    public function deleteProductsForEntity(array $ids): Response
    {
        return $this->http->delete(self::NAMESPACE . '/bulk', [], ['list_products' => $ids]);
    }
}
