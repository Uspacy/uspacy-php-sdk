<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * CRM product measurement units service.
 *
 * Mirrors the JS SDK's CrmProductsUnitService (`/crm/v1/static/measurement-units`).
 */
class CrmProductsUnitService extends Service
{
    private const NAMESPACE = '/crm/v1/static/measurement-units';

    /**
     * Get all measurement units.
     */
    public function getProductUnits(): Response
    {
        return $this->http->get(self::NAMESPACE);
    }

    /**
     * Create a measurement unit.
     */
    public function createProductUnit(array $data): Response
    {
        return $this->http->post(self::NAMESPACE, $data);
    }

    /**
     * Update a measurement unit.
     *
     * @param  int|string  $id
     */
    public function updateProductUnit($id, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/{$id}", $data);
    }

    /**
     * Delete a measurement unit.
     *
     * @param  int|string  $id
     */
    public function deleteProductUnit($id): Response
    {
        return $this->http->delete(self::NAMESPACE . "/{$id}");
    }
}
