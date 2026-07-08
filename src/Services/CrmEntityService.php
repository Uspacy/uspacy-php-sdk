<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\Http\Client\HttpClient;

/**
 * CRM entity service scoped to a single built-in entity type
 * (deals, leads, contacts, companies).
 *
 * Mirrors the JS SDK's per-entity services (CrmDealsService, CrmLeadsService,
 * CrmContactsService, CrmCompaniesService), which are all the same surface bound
 * to a different `/crm/v1/entities/{type}` namespace.
 */
class CrmEntityService extends Service
{
    public function __construct(
        HttpClient $http,
        private string $entityType,
    ) {
        parent::__construct($http);
    }

    /**
     * Get a page of entities.
     *
     * @param  array  $params  query parameters (page, list, filters, ...)
     */
    public function getEntities(array $params = []): Response
    {
        return $this->http->get($this->namespace(), $params);
    }

    /**
     * Create an entity.
     */
    public function createEntity(array $data): Response
    {
        return $this->http->post($this->namespace(), $data);
    }

    /**
     * Update an entity.
     *
     * @param  int|string  $id
     */
    public function updateEntity($id, array $data): Response
    {
        return $this->http->patch($this->namespace() . "/{$id}", $data);
    }

    /**
     * Delete an entity.
     *
     * @param  int|string  $id
     */
    public function deleteEntity($id): Response
    {
        return $this->http->delete($this->namespace() . "/{$id}");
    }

    /**
     * Mass delete entities.
     *
     * @param  array  $entityIds  ids to delete
     * @param  array  $exceptIds  ids to exclude when $all is true
     * @param  bool  $all  delete every entity matching $params
     * @param  array  $params  query filters used when $all is true
     */
    public function massDeletion(array $entityIds = [], array $exceptIds = [], bool $all = false, array $params = []): Response
    {
        return $this->http->delete($this->namespace() . '/mass_deletion', [
            'all' => $all,
            'entity_ids' => $entityIds,
            'except_ids' => $exceptIds,
        ], $params);
    }

    /**
     * Mass edit entities.
     *
     * @param  array  $payload  fields to apply
     * @param  array  $settings  editing settings
     * @param  array  $entityIds  ids to edit
     * @param  array  $exceptIds  ids to exclude when $all is true
     * @param  bool  $all  edit every entity matching $params
     * @param  array  $params  query filters used when $all is true
     */
    public function massEditing(array $payload, array $settings = [], array $entityIds = [], array $exceptIds = [], bool $all = false, array $params = []): Response
    {
        return $this->http->patch($this->namespace() . '/mass_edit', [
            'all' => $all,
            'entity_ids' => $entityIds,
            'except_ids' => $exceptIds,
            'payload' => $payload,
            'settings' => $settings,
        ], $params);
    }

    /**
     * Get the fields of this entity type.
     */
    public function getFields(): Response
    {
        return $this->http->get($this->namespace() . '/fields');
    }

    /**
     * Create a field.
     */
    public function createField(array $data): Response
    {
        return $this->http->post($this->namespace() . '/fields', $data);
    }

    /**
     * Update a field by its code.
     */
    public function updateField(string $code, array $data): Response
    {
        return $this->http->patch($this->namespace() . "/fields/{$code}", $data);
    }

    /**
     * Delete a field by its code.
     */
    public function deleteField(string $code): Response
    {
        return $this->http->delete($this->namespace() . "/fields/{$code}");
    }

    /**
     * Add/replace list (dropdown) values for a field.
     *
     * @param  array  $values  list values payload
     */
    public function updateListValues(string $fieldCode, array $values): Response
    {
        return $this->http->post($this->namespace() . "/lists/{$fieldCode}", $values);
    }

    /**
     * Delete a single list (dropdown) value from a field.
     */
    public function deleteListValue(string $fieldCode, string $value): Response
    {
        return $this->http->delete($this->namespace() . "/lists/{$fieldCode}/{$value}");
    }

    /**
     * Get entities that belong to a kanban stage.
     *
     * @param  int|string  $stageId
     */
    public function getByStage($stageId): Response
    {
        return $this->http->get($this->namespace() . "/kanban/stage/{$stageId}");
    }

    /**
     * Move an entity from its current kanban stage to another.
     *
     * @param  int|string  $entityId
     * @param  int|string  $stageId
     * @param  int|string|null  $reasonId  fail reason id, when the target stage requires one
     */
    public function moveFromStageToStage($entityId, $stageId, $reasonId = null): Response
    {
        return $this->http->post(
            $this->namespace() . "/{$entityId}/move/stage/{$stageId}",
            ['reason_id' => $reasonId],
        );
    }

    private function namespace(): string
    {
        return "/crm/v1/entities/{$this->entityType}";
    }
}
