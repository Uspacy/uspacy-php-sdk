<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\DTOs\Crm\ReasonDTO;
use Uspacy\SDK\DTOs\Crm\ReasonsDTO;
use Uspacy\SDK\DTOs\Crm\StageDTO;
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
     *
     * @return array<int, StageDTO>
     */
    public function getStages(): array
    {
        $data = $this->http->get($this->namespace() . '/kanban/stage')->json() ?? [];

        return array_map([StageDTO::class, 'fromArray'], $data['data'] ?? []);
    }

    /**
     * Create a kanban stage.
     */
    public function createStage(array $data): StageDTO
    {
        return StageDTO::fromArray($this->http->post($this->namespace() . '/kanban/stage', $data)->json() ?? []);
    }

    /**
     * Update a kanban stage.
     *
     * @param  int|string  $id
     */
    public function updateStage($id, array $data): StageDTO
    {
        return StageDTO::fromArray($this->http->patch($this->namespace() . "/kanban/stage/{$id}", $data)->json() ?? []);
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
    public function getReasons($entityId): ReasonsDTO
    {
        return ReasonsDTO::fromArray($this->http->get(self::REASONS_NAMESPACE . "/{$entityId}")->json() ?? []);
    }

    /**
     * Create a fail reason for an entity record.
     *
     * @param  int|string  $entityId
     */
    public function createReason($entityId, array $data): ReasonDTO
    {
        return ReasonDTO::fromArray($this->http->post(self::REASONS_NAMESPACE . "/{$entityId}", $data)->json() ?? []);
    }

    /**
     * Update a fail reason.
     *
     * @param  int|string  $id
     */
    public function updateReason($id, array $data): ReasonDTO
    {
        return ReasonDTO::fromArray($this->http->patch(self::REASONS_NAMESPACE . "/{$id}", $data)->json() ?? []);
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
