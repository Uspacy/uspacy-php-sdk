<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

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
     */
    public function getProductTaxes(): Response
    {
        return $this->http->get(self::NAMESPACE);
    }

    /**
     * Create a tax.
     */
    public function createProductTax(array $data): Response
    {
        return $this->http->post(self::NAMESPACE, $data);
    }

    /**
     * Update a tax.
     *
     * @param  int|string  $id
     */
    public function updateProductTax($id, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/{$id}", $data);
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
