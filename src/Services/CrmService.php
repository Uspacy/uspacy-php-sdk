<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * CRM service.
 *
 * Covers the `crm/v1` module: entities, the built-in contacts/companies/leads/deals
 * entity types, fields, funnels, kanban stages, list values, fail reasons, calls,
 * products and CRM tasks. Mirrors the Go SDK's `crm.go`/`product.go` surface.
 */
class CrmService extends Service
{
    private const NAMESPACE = '/crm/v1';

    /**
     * Get the list of available CRM entity definitions.
     */
    public function getEntityTypes(): Response
    {
        return $this->http->get(self::NAMESPACE . '/entity');
    }

    /**
     * Get a page of entities of the given type.
     *
     * @param  string  $entityType  e.g. contacts, companies, leads, deals or a custom type
     * @param  array  $params  query parameters (page, list, filters, ...)
     */
    public function getEntities(string $entityType, array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . "/entities/{$entityType}/", $params);
    }

    public function getContacts(array $params = []): Response
    {
        return $this->getEntities('contacts', $params);
    }

    public function getCompanies(array $params = []): Response
    {
        return $this->getEntities('companies', $params);
    }

    public function getLeads(array $params = []): Response
    {
        return $this->getEntities('leads', $params);
    }

    public function getDeals(array $params = []): Response
    {
        return $this->getEntities('deals', $params);
    }

    /**
     * Create an entity of the given type.
     */
    public function createEntity(string $entityType, array $data): Response
    {
        return $this->http->post(self::NAMESPACE . "/entities/{$entityType}/", $data);
    }

    public function createContact(array $data): Response
    {
        return $this->createEntity('contacts', $data);
    }

    public function createCompany(array $data): Response
    {
        return $this->createEntity('companies', $data);
    }

    public function createLead(array $data): Response
    {
        return $this->createEntity('leads', $data);
    }

    public function createDeal(array $data): Response
    {
        return $this->createEntity('deals', $data);
    }

    /**
     * Partially update a single entity.
     *
     * @param  int|string  $id
     */
    public function patchEntity(string $entityType, $id, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/entities/{$entityType}/{$id}", $data);
    }

    /**
     * Mass edit entities of the given type.
     */
    public function massEditEntities(string $entityType, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/entities/{$entityType}/mass_edit", $data);
    }

    /**
     * Get all fields for an entity type.
     */
    public function getFields(string $entityType): Response
    {
        return $this->http->get(self::NAMESPACE . "/entities/{$entityType}/fields/");
    }

    /**
     * Get a single field definition by its type/code.
     */
    public function getField(string $entityType, string $fieldType): Response
    {
        return $this->http->get(self::NAMESPACE . "/entities/{$entityType}/fields/{$fieldType}/");
    }

    /**
     * Create a field for an entity type.
     */
    public function createField(string $entityType, array $data): Response
    {
        return $this->http->post(self::NAMESPACE . "/entities/{$entityType}/fields", $data);
    }

    /**
     * Delete a field by its code.
     */
    public function deleteField(string $entityType, string $fieldCode): Response
    {
        return $this->http->delete(self::NAMESPACE . "/entities/{$entityType}/fields/{$fieldCode}/");
    }

    /**
     * Get all funnels for an entity type.
     */
    public function getFunnels(string $entityType): Response
    {
        return $this->http->get(self::NAMESPACE . "/entities/{$entityType}/funnel");
    }

    /**
     * Create a funnel for an entity type.
     */
    public function createFunnel(string $entityType, array $data): Response
    {
        return $this->http->post(self::NAMESPACE . "/entities/{$entityType}/funnel", $data);
    }

    /**
     * Get all kanban stages for an entity type.
     */
    public function getFunnelStages(string $entityType): Response
    {
        return $this->http->get(self::NAMESPACE . "/entities/{$entityType}/kanban/stage/");
    }

    /**
     * Get kanban stages for a specific funnel.
     */
    public function getFunnelStagesByFunnelId(string $entityType, int $funnelId): Response
    {
        return $this->http->get(self::NAMESPACE . "/entities/{$entityType}/kanban/stage/", ['funnel_id' => $funnelId]);
    }

    /**
     * Create a kanban stage for an entity type.
     */
    public function createFunnelStage(string $entityType, array $data): Response
    {
        return $this->http->post(self::NAMESPACE . "/entities/{$entityType}/kanban/stage/", $data);
    }

    /**
     * Update a kanban stage.
     *
     * @param  int|string  $stageId
     */
    public function patchFunnelStage(string $entityType, $stageId, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/entities/{$entityType}/kanban/stage/{$stageId}", $data);
    }

    /**
     * Move an entity to another kanban stage.
     *
     * @param  int|string  $entityId
     * @param  array  $reason  optional fail reason payload
     */
    public function moveFunnelStage(string $entityType, $entityId, string $stageId, array $reason = []): Response
    {
        return $this->http->post(self::NAMESPACE . "/entities/{$entityType}/{$entityId}/move/stage/{$stageId}", $reason);
    }

    /**
     * Get list (dropdown) values for a field.
     */
    public function getListValues(string $entityType, string $listName): Response
    {
        return $this->http->get(self::NAMESPACE . "/entities/{$entityType}/lists/{$listName}");
    }

    /**
     * Create a list (dropdown) value for a field.
     */
    public function createListValue(string $entityType, string $listName, array $data): Response
    {
        return $this->http->post(self::NAMESPACE . "/entities/{$entityType}/lists/{$listName}", $data);
    }

    /**
     * Create a fail reason for a kanban stage group.
     *
     * @param  int|string  $stageGroupId
     */
    public function createFailReason($stageGroupId, array $data): Response
    {
        return $this->http->post(self::NAMESPACE . "/reasons/{$stageGroupId}", $data);
    }

    /**
     * Register a call event.
     */
    public function createCall(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/events/call', $data);
    }

    /**
     * Create a CRM task (static tasks entity).
     */
    public function createTask(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/static/tasks/', $data);
    }

    /**
     * Update a CRM task.
     *
     * @param  int|string  $id
     */
    public function patchTask($id, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/static/tasks/{$id}", $data);
    }

    /**
     * Get a product by id.
     *
     * @param  int|string  $id
     */
    public function getProduct($id): Response
    {
        return $this->http->get(self::NAMESPACE . "/static/products/{$id}");
    }

    /**
     * Create a product.
     */
    public function createProduct(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/static/products/', $data);
    }
}
