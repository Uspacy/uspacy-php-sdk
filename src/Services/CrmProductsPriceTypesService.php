<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\DTOs\Crm\PriceTypeDTO;

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
     *
     * @return array<int, PriceTypeDTO>
     */
    public function getProductPriceTypes(): array
    {
        $data = $this->http->get(self::NAMESPACE)->json() ?? [];

        return array_map([PriceTypeDTO::class, 'fromArray'], $data['data'] ?? []);
    }

    /**
     * Create a price type.
     */
    public function createProductPriceType(array $data): PriceTypeDTO
    {
        return PriceTypeDTO::fromArray($this->http->post(self::NAMESPACE, $data)->json() ?? []);
    }

    /**
     * Update a price type.
     *
     * @param  int|string  $id
     */
    public function updateProductPriceType($id, array $data): PriceTypeDTO
    {
        return PriceTypeDTO::fromArray($this->http->patch(self::NAMESPACE . "/{$id}", $data)->json() ?? []);
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
