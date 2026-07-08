<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\DTOs\Crm\FunnelDTO;
use Uspacy\SDK\DTOs\Crm\ReasonDTO;
use Uspacy\SDK\DTOs\Crm\StageDTO;
use Uspacy\SDK\Http\Client\HttpClient;

/**
 * CRM funnels service scoped to a single entity type (deals, leads).
 *
 * Mirrors the JS SDK's CrmDealsFunnelsService / CrmLeadsFunnelsService: funnel
 * CRUD, kanban stage CRUD and fail reasons managed per funnel.
 */
class CrmFunnelsService extends Service
{
    private const REASONS_NAMESPACE = '/crm/v1/reasons';

    public function __construct(
        HttpClient $http,
        private string $entityType,
    ) {
        parent::__construct($http);
    }

    /**
     * Get all funnels for this entity type.
     *
     * @return array<int, FunnelDTO>
     */
    public function getFunnels(): array
    {
        $data = $this->http->get($this->namespace() . '/funnel')->json() ?? [];

        return array_map([FunnelDTO::class, 'fromArray'], $data);
    }

    /**
     * Create a funnel.
     */
    public function createFunnel(array $data): FunnelDTO
    {
        return FunnelDTO::fromArray($this->http->post($this->namespace() . '/funnel', $data)->json() ?? []);
    }

    /**
     * Update a funnel.
     *
     * @param  int|string  $id
     */
    public function updateFunnel($id, array $data): FunnelDTO
    {
        return FunnelDTO::fromArray($this->http->patch($this->namespace() . "/funnel/{$id}", $data)->json() ?? []);
    }

    /**
     * Delete a funnel.
     *
     * @param  int|string  $id
     */
    public function deleteFunnel($id): Response
    {
        return $this->http->delete($this->namespace() . "/funnel/{$id}");
    }

    /**
     * Get the kanban stages of a specific funnel.
     *
     * @param  int|string  $funnelId
     * @return array<int, StageDTO>
     */
    public function getStagesByFunnel($funnelId): array
    {
        $data = $this->http->get($this->namespace() . '/kanban/stage', ['funnel_id' => $funnelId])->json() ?? [];

        return array_map([StageDTO::class, 'fromArray'], $data['data'] ?? []);
    }

    /**
     * Create a kanban stage for a funnel.
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
     * Create a fail reason for a funnel stage.
     *
     * @param  int|string  $funnelId
     */
    public function createStageReason($funnelId, array $data): ReasonDTO
    {
        return ReasonDTO::fromArray($this->http->post(self::REASONS_NAMESPACE . "/{$funnelId}", $data)->json() ?? []);
    }

    /**
     * Update a fail reason.
     *
     * @param  int|string  $id
     */
    public function updateStageReason($id, array $data): ReasonDTO
    {
        return ReasonDTO::fromArray($this->http->patch(self::REASONS_NAMESPACE . "/{$id}", $data)->json() ?? []);
    }

    /**
     * Delete a fail reason.
     *
     * @param  int|string  $id
     */
    public function deleteStageReason($id): Response
    {
        return $this->http->delete(self::REASONS_NAMESPACE . "/{$id}");
    }

    private function namespace(): string
    {
        return "/crm/v1/entities/{$this->entityType}";
    }
}
