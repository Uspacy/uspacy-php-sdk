<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\DTOs\Crm\UnitDTO;

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
     *
     * @return array<int, UnitDTO>
     */
    public function getProductUnits(): array
    {
        $data = $this->http->get(self::NAMESPACE)->json() ?? [];

        return array_map([UnitDTO::class, 'fromArray'], $data['data'] ?? []);
    }

    /**
     * Create a measurement unit.
     */
    public function createProductUnit(array $data): UnitDTO
    {
        return UnitDTO::fromArray($this->http->post(self::NAMESPACE, $data)->json() ?? []);
    }

    /**
     * Update a measurement unit.
     *
     * @param  int|string  $id
     */
    public function updateProductUnit($id, array $data): UnitDTO
    {
        return UnitDTO::fromArray($this->http->patch(self::NAMESPACE . "/{$id}", $data)->json() ?? []);
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
