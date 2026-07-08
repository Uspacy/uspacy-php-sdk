<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * CRM requisites service.
 *
 * Mirrors the JS SDK's CrmRequisitesService (`/crm/v1/requisites`): requisite
 * templates, card requisites, and their nested bank requisites.
 */
class CrmRequisitesService extends Service
{
    private const NAMESPACE = '/crm/v1/requisites';

    /**
     * Get requisite templates.
     *
     * @param  array  $params  pagination params (page, list)
     */
    public function getTemplates(array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . '/templates', $params);
    }

    /**
     * Get card requisites.
     *
     * @param  array  $params  requisite list filter params
     */
    public function getCardRequisites(array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE, $params);
    }

    /**
     * Create a card requisite.
     *
     * @param  array  $params  requisite list filter params
     */
    public function createCardRequisites(array $data, array $params = []): Response
    {
        return $this->http->post(self::NAMESPACE, $data, $params);
    }

    /**
     * Update a card requisite.
     *
     * @param  int|string  $id
     */
    public function updateCardRequisites($id, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/{$id}", $data);
    }

    /**
     * Attach a card requisite reference.
     *
     * @param  array  $params  requisite list filter params
     */
    public function attachCardRequisites(array $params = []): Response
    {
        return $this->http->post(self::NAMESPACE . '/references/attach-reference', [], $params);
    }

    /**
     * Delete a card requisite.
     *
     * @param  int|string  $id
     */
    public function deleteCardRequisites($id): Response
    {
        return $this->http->delete(self::NAMESPACE . "/{$id}");
    }

    /**
     * Create a bank requisite under a card requisite.
     *
     * @param  int|string  $requisiteId
     */
    public function createBankRequisites($requisiteId, array $data): Response
    {
        return $this->http->post(self::NAMESPACE . "/{$requisiteId}/bank_requisites", $data);
    }

    /**
     * Update a bank requisite.
     *
     * @param  int|string  $requisiteId
     * @param  int|string  $bankRequisiteId
     */
    public function updateBankRequisites($requisiteId, $bankRequisiteId, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/{$requisiteId}/bank_requisites/{$bankRequisiteId}", $data);
    }

    /**
     * Delete a bank requisite.
     *
     * @param  int|string  $requisiteId
     * @param  int|string  $bankRequisiteId
     */
    public function deleteBankRequisites($requisiteId, $bankRequisiteId): Response
    {
        return $this->http->delete(self::NAMESPACE . "/{$requisiteId}/bank_requisites/{$bankRequisiteId}");
    }

    /**
     * Attach a bank requisite reference.
     *
     * @param  int|string  $requisiteId
     * @param  array  $params  requisite list filter params
     */
    public function attachBankRequisites($requisiteId, array $params = []): Response
    {
        return $this->http->post(self::NAMESPACE . "/{$requisiteId}/bank_requisites/references/attach-reference", [], $params);
    }
}
