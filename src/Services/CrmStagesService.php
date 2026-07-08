<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\Http\Client\HttpClient;

/**
 * CRM kanban stages service scoped to a single entity type (deals, leads).
 *
 * Mirrors the JS SDK's CrmDealsStagesService / CrmLeadsStagesService: stage CRUD
 * and fail reasons managed per entity record.
 */
class CrmStagesService extends Service
{
    private const REASONS_NAMESPACE = '/crm/v1/reasons';

    public function __construct(
        HttpClient $http,
        private string $entityType,
    ) {
        parent::__construct($http);
    }

    /**
     * Get all kanban stages for this entity type.
     */
    public function getStages(): Response
    {
        return $this->http->get($this->namespace() . '/kanban/stage');
    }

    /**
     * Create a kanban stage.
     */
    public function createStage(array $data): Response
    {
        return $this->http->post($this->namespace() . '/kanban/stage', $data);
    }

    /**
     * Update a kanban stage.
     *
     * @param  int|string  $id
     */
    public function updateStage($id, array $data): Response
    {
        return $this->http->patch($this->namespace() . "/kanban/stage/{$id}", $data);
    }

    /**
     * Delete a kanban stage.
     *
     * @param  int|string  $id
     */
    public function deleteStage($id): Response
    {
        return $this->http->delete($this->namespace() . "/kanban/stage/{$id}");
    }

    /**
     * Get the fail reasons recorded for an entity record.
     *
     * @param  int|string  $entityId
     */
    public function getReasons($entityId): Response
    {
        return $this->http->get(self::REASONS_NAMESPACE . "/{$entityId}");
    }

    /**
     * Create a fail reason for an entity record.
     *
     * @param  int|string  $entityId
     */
    public function createReason($entityId, array $data): Response
    {
        return $this->http->post(self::REASONS_NAMESPACE . "/{$entityId}", $data);
    }

    /**
     * Update a fail reason.
     *
     * @param  int|string  $id
     */
    public function updateReason($id, array $data): Response
    {
        return $this->http->patch(self::REASONS_NAMESPACE . "/{$id}", $data);
    }

    /**
     * Delete a fail reason.
     *
     * @param  int|string  $id
     */
    public function deleteReason($id): Response
    {
        return $this->http->delete(self::REASONS_NAMESPACE . "/{$id}");
    }

    private function namespace(): string
    {
        return "/crm/v1/entities/{$this->entityType}";
    }
}
