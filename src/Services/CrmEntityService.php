<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\DTOs\Collection;
use Uspacy\SDK\DTOs\Crm\EntityDTO;
use Uspacy\SDK\DTOs\Crm\FieldDTO;
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
     * @return Collection<EntityDTO>
     */
    public function getEntities(array $params = []): Collection
    {
        return Collection::fromArray(
            $this->http->get($this->namespace(), $params)->json() ?? [],
            [EntityDTO::class, 'fromArray'],
        );
    }

    /**
     * Create an entity.
     */
    public function createEntity(array $data): EntityDTO
    {
        return EntityDTO::fromArray($this->http->post($this->namespace(), $data)->json() ?? []);
    }

    /**
     * Update an entity.
     *
     * @param  int|string  $id
     */
    public function updateEntity($id, array $data): EntityDTO
    {
        return EntityDTO::fromArray($this->http->patch($this->namespace() . "/{$id}", $data)->json() ?? []);
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
     *
     * @return array<int, FieldDTO>
     */
    public function getFields(): array
    {
        $data = $this->http->get($this->namespace() . '/fields')->json() ?? [];

        return array_map([FieldDTO::class, 'fromArray'], $data['data'] ?? []);
    }

    /**
     * Create a field.
     */
    public function createField(array $data): FieldDTO
    {
        return FieldDTO::fromArray($this->http->post($this->namespace() . '/fields', $data)->json() ?? []);
    }

    /**
     * Update a field by its code.
     */
    public function updateField(string $code, array $data): FieldDTO
    {
        return FieldDTO::fromArray($this->http->patch($this->namespace() . "/fields/{$code}", $data)->json() ?? []);
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
     * @return Collection<EntityDTO>
     */
    public function getByStage($stageId): Collection
    {
        return Collection::fromArray(
            $this->http->get($this->namespace() . "/kanban/stage/{$stageId}")->json() ?? [],
            [EntityDTO::class, 'fromArray'],
        );
    }

    /**
     * Move an entity from its current kanban stage to another.
     *
     * @param  int|string  $entityId
     * @param  int|string  $stageId
     * @param  int|string|null  $reasonId  fail reason id, when the target stage requires one
     */
    public function moveFromStageToStage($entityId, $stageId, $reasonId = null): EntityDTO
    {
        return EntityDTO::fromArray($this->http->post(
            $this->namespace() . "/{$entityId}/move/stage/{$stageId}",
            ['reason_id' => $reasonId],
        )->json() ?? []);
    }

    private function namespace(): string
    {
        return "/crm/v1/entities/{$this->entityType}";
    }
}
