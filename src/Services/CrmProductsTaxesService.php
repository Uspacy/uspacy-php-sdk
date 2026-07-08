<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\DTOs\Crm\TaxDTO;

/**
 * CRM product taxes service.
 *
 * Mirrors the JS SDK's CrmProductsTaxesService (`/crm/v1/static/taxes`).
 */
class CrmProductsTaxesService extends Service
{
    private const NAMESPACE = '/crm/v1/static/taxes';

    /**
     * Get all taxes.
     *
     * @return array<int, TaxDTO>
     */
    public function getProductTaxes(): array
    {
        $data = $this->http->get(self::NAMESPACE)->json() ?? [];

        return array_map([TaxDTO::class, 'fromArray'], $data['data'] ?? []);
    }

    /**
     * Create a tax.
     */
    public function createProductTax(array $data): TaxDTO
    {
        return TaxDTO::fromArray($this->http->post(self::NAMESPACE, $data)->json() ?? []);
    }

    /**
     * Update a tax.
     *
     * @param  int|string  $id
     */
    public function updateProductTax($id, array $data): TaxDTO
    {
        return TaxDTO::fromArray($this->http->patch(self::NAMESPACE . "/{$id}", $data)->json() ?? []);
    }

    /**
     * Delete a tax.
     *
     * @param  int|string  $id
     */
    public function deleteProductTax($id): Response
    {
        return $this->http->delete(self::NAMESPACE . "/{$id}");
    }
}
