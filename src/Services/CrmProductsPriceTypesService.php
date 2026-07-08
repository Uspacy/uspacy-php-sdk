<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * CRM product price types service.
 *
 * Mirrors the JS SDK's CrmProductsPriceTypesService (`/crm/v1/static/product-price-types`).
 */
class CrmProductsPriceTypesService extends Service
{
    private const NAMESPACE = '/crm/v1/static/product-price-types';

    /**
     * Get all price types.
     */
    public function getProductPriceTypes(): Response
    {
        return $this->http->get(self::NAMESPACE);
    }

    /**
     * Create a price type.
     */
    public function createProductPriceType(array $data): Response
    {
        return $this->http->post(self::NAMESPACE, $data);
    }

    /**
     * Update a price type.
     *
     * @param  int|string  $id
     */
    public function updateProductPriceType($id, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/{$id}", $data);
    }

    /**
     * Delete a price type.
     *
     * @param  int|string  $id
     */
    public function deleteProductPriceType($id): Response
    {
        return $this->http->delete(self::NAMESPACE . "/{$id}");
    }
}
