<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * CRM products service.
 *
 * Mirrors the JS SDK's CrmProductsService: the products catalog lives under
 * `/crm/v1/static/products`, while product fields/list-values are managed through
 * the dynamic entity namespace `/crm/v1/entities/products`.
 */
class CrmProductsService extends Service
{
    private const NAMESPACE = '/crm/v1/static/products';

    private const DYNAMIC_NAMESPACE = '/crm/v1/entities/products';

    /**
     * Get a page of products.
     *
     * @param  array  $params  query parameters (page, list, filters, ...)
     */
    public function getProducts(array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE, $params);
    }

    /**
     * Create a product.
     */
    public function createProduct(array $data): Response
    {
        return $this->http->post(self::NAMESPACE, $data);
    }

    /**
     * Update a product.
     *
     * @param  int|string  $id
     */
    public function updateProduct($id, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/{$id}", $data);
    }

    /**
     * Delete a product.
     *
     * @param  int|string  $id
     */
    public function deleteProduct($id): Response
    {
        return $this->http->delete(self::NAMESPACE . "/{$id}");
    }

    /**
     * Mass delete products.
     *
     * @param  array  $params  query filters used when $all is true
     */
    public function massDeletion(array $entityIds = [], array $exceptIds = [], bool $all = false, array $params = []): Response
    {
        return $this->http->delete(self::NAMESPACE . '/mass_deletion', [
            'all' => $all,
            'entity_ids' => $entityIds,
            'except_ids' => $exceptIds,
        ], $params);
    }

    /**
     * Mass edit products.
     *
     * @param  array  $params  query filters used when $all is true
     */
    public function massEditing(array $payload, array $settings = [], array $entityIds = [], array $exceptIds = [], bool $all = false, array $params = []): Response
    {
        return $this->http->patch(self::NAMESPACE . '/mass_edit', [
            'all' => $all,
            'entity_ids' => $entityIds,
            'except_ids' => $exceptIds,
            'payload' => $payload,
            'settings' => $settings,
        ], $params);
    }

    /**
     * Get product fields.
     */
    public function getFields(): Response
    {
        return $this->http->get(self::DYNAMIC_NAMESPACE . '/fields');
    }

    /**
     * Create a product field.
     */
    public function createField(array $data): Response
    {
        return $this->http->post(self::DYNAMIC_NAMESPACE . '/fields', $data);
    }

    /**
     * Update a product field by its code.
     */
    public function updateField(string $code, array $data): Response
    {
        return $this->http->patch(self::DYNAMIC_NAMESPACE . "/fields/{$code}", $data);
    }

    /**
     * Delete a product field by its code.
     */
    public function deleteField(string $code): Response
    {
        return $this->http->delete(self::DYNAMIC_NAMESPACE . "/fields/{$code}");
    }

    /**
     * Add/replace list (dropdown) values for a product field.
     */
    public function updateListValues(string $fieldCode, array $values): Response
    {
        return $this->http->post(self::DYNAMIC_NAMESPACE . "/lists/{$fieldCode}", $values);
    }

    /**
     * Delete a single list (dropdown) value from a product field.
     */
    public function deleteListValue(string $fieldCode, string $value): Response
    {
        return $this->http->delete(self::DYNAMIC_NAMESPACE . "/lists/{$fieldCode}/{$value}");
    }
}
