<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Smart objects (custom CRM tables) service.
 *
 * Smart objects live under the `crm/v1` module. Mirrors the Go SDK's `smart-objects.go`.
 */
class SmartObjectsService extends Service
{
    private const NAMESPACE = '/crm/v1';

    /**
     * Create a new smart object (custom table definition).
     */
    public function createSmartObject(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/entity', $data);
    }

    /**
     * Create an entity inside a smart object table.
     */
    public function createEntity(string $tableName, array $data): Response
    {
        return $this->http->post(self::NAMESPACE . "/entities/{$tableName}/", $data);
    }

    /**
     * Get the fields of a smart object table.
     */
    public function getFields(string $tableName): Response
    {
        return $this->http->get(self::NAMESPACE . "/entities/{$tableName}/fields");
    }

    /**
     * Create a field on a smart object table.
     */
    public function createField(string $tableName, array $data): Response
    {
        return $this->http->post(self::NAMESPACE . "/entities/{$tableName}/fields", $data);
    }

    /**
     * Create a list (dropdown) value on a smart object field.
     */
    public function createListValue(string $tableName, string $listName, array $data): Response
    {
        return $this->http->post(self::NAMESPACE . "/entities/{$tableName}/lists/{$listName}", $data);
    }

    /**
     * Get the kanban stages of a smart object table.
     */
    public function getStages(string $tableName): Response
    {
        return $this->http->get(self::NAMESPACE . "/entities/{$tableName}/kanban/stage/");
    }

    /**
     * Create a kanban stage on a smart object table.
     */
    public function createStage(string $tableName, array $data): Response
    {
        return $this->http->post(self::NAMESPACE . "/entities/{$tableName}/kanban/stage/", $data);
    }

    /**
     * Move a smart object entity to another kanban stage.
     *
     * @param  int|string  $entityId
     */
    public function moveStage(string $tableName, $entityId, string $stageId, array $reason = []): Response
    {
        return $this->http->post(self::NAMESPACE . "/entities/{$tableName}/{$entityId}/move/stage/{$stageId}", $reason);
    }
}
