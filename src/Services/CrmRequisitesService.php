<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\DTOs\Collection;
use Uspacy\SDK\DTOs\Crm\RequisiteDTO;
use Uspacy\SDK\DTOs\Crm\RequisiteTemplateDTO;

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
     * @return Collection<RequisiteTemplateDTO>
     */
    public function getTemplates(array $params = []): Collection
    {
        return Collection::fromArray(
            $this->http->get(self::NAMESPACE . '/templates', $params)->json() ?? [],
            [RequisiteTemplateDTO::class, 'fromArray'],
        );
    }

    /**
     * Get card requisites.
     *
     * @param  array  $params  requisite list filter params
     * @return Collection<RequisiteDTO>
     */
    public function getCardRequisites(array $params = []): Collection
    {
        return Collection::fromArray(
            $this->http->get(self::NAMESPACE, $params)->json() ?? [],
            [RequisiteDTO::class, 'fromArray'],
        );
    }

    /**
     * Create a card requisite.
     *
     * @param  array  $params  requisite list filter params
     */
    public function createCardRequisites(array $data, array $params = []): RequisiteDTO
    {
        return RequisiteDTO::fromArray($this->http->post(self::NAMESPACE, $data, $params)->json() ?? []);
    }

    /**
     * Update a card requisite.
     *
     * @param  int|string  $id
     */
    public function updateCardRequisites($id, array $data): RequisiteDTO
    {
        return RequisiteDTO::fromArray($this->http->patch(self::NAMESPACE . "/{$id}", $data)->json() ?? []);
    }

    /**
     * Attach a card requisite reference.
     *
     * @param  array  $params  requisite list filter params
     */
    public function attachCardRequisites(array $params = []): RequisiteDTO
    {
        return RequisiteDTO::fromArray($this->http->post(self::NAMESPACE . '/references/attach-reference', [], $params)->json() ?? []);
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
    public function createBankRequisites($requisiteId, array $data): RequisiteDTO
    {
        return RequisiteDTO::fromArray($this->http->post(self::NAMESPACE . "/{$requisiteId}/bank_requisites", $data)->json() ?? []);
    }

    /**
     * Update a bank requisite.
     *
     * @param  int|string  $requisiteId
     * @param  int|string  $bankRequisiteId
     */
    public function updateBankRequisites($requisiteId, $bankRequisiteId, array $data): RequisiteDTO
    {
        return RequisiteDTO::fromArray($this->http->patch(self::NAMESPACE . "/{$requisiteId}/bank_requisites/{$bankRequisiteId}", $data)->json() ?? []);
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
    public function attachBankRequisites($requisiteId, array $params = []): RequisiteDTO
    {
        return RequisiteDTO::fromArray($this->http->post(self::NAMESPACE . "/{$requisiteId}/bank_requisites/references/attach-reference", [], $params)->json() ?? []);
    }
}
