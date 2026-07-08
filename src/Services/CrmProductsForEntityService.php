<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

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
     * @param  int|string  $entityId
     */
    public function getInfoProductsForEntity(string $entityType, $entityId): Response
    {
        return $this->http->get(self::INFO_NAMESPACE, [
            'entity_type' => $entityType,
            'entity_id' => $entityId,
        ]);
    }

    /**
     * Update aggregated product info.
     *
     * @param  int|string  $id
     */
    public function updateInfoProductForEntity($id, array $info): Response
    {
        return $this->http->patch(self::INFO_NAMESPACE . "/{$id}", $info);
    }

    /**
     * Get all line products.
     */
    public function getProductsForEntity(): Response
    {
        return $this->http->get(self::NAMESPACE);
    }

    /**
     * Get a single line product.
     *
     * @param  int|string  $id
     */
    public function getProductForEntity($id): Response
    {
        return $this->http->get(self::NAMESPACE . "/{$id}");
    }

    /**
     * Create a line product.
     */
    public function createProductForEntity(array $data): Response
    {
        return $this->http->post(self::NAMESPACE, $data);
    }

    /**
     * Update a line product.
     *
     * @param  int|string  $id
     */
    public function updateProductForEntity($id, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/{$id}", $data);
    }

    /**
     * Bulk create line products.
     *
     * @param  array  $listProducts  list of line products
     */
    public function createProductsForEntity(array $listProducts): Response
    {
        return $this->http->post(self::NAMESPACE . '/bulk', ['list_products' => $listProducts]);
    }

    /**
     * Bulk update line products.
     *
     * @param  array  $listProducts  list of line products
     */
    public function updateProductsForEntity(array $listProducts): Response
    {
        return $this->http->patch(self::NAMESPACE . '/bulk', ['list_products' => $listProducts]);
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
