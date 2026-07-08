<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * History (change log) service.
 *
 * Mirrors the JS SDK's HistoryService (`/history/v1`).
 */
class HistoryService extends Service
{
    private const NAMESPACE = '/history/v1';

    /**
     * Get the change history for an entity record.
     *
     * @param  string  $service  originating service (e.g. crm, tasks)
     * @param  string  $entityTableName  entity table name
     * @param  int|string  $id  record id
     * @param  array  $params  query params (page, list, action)
     */
    public function getChangesHistory(string $service, string $entityTableName, $id, array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . "/{$service}/{$entityTableName}/{$id}", $params);
    }
}
