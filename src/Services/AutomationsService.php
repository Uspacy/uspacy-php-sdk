<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Automations service.
 *
 * Mirrors the JS SDK's AutomationsService: automations ("workers") and workflows
 * ("processes") under the `/automations-backend/v1` module.
 */
class AutomationsService extends Service
{
    private const WORKERS = '/automations-backend/v1/workers';

    private const PROCESSES = '/automations-backend/v1/processes';

    /**
     * Get automations (workers).
     *
     * @param  array  $params  query params (page, list, search, sortBy, sortOrder)
     */
    public function getAutomations(array $params = []): Response
    {
        return $this->http->get(self::WORKERS, $params);
    }

    /**
     * Delete an automation.
     *
     * @param  int|string  $id
     */
    public function deleteAutomation($id): Response
    {
        return $this->http->delete(self::WORKERS . "/{$id}");
    }

    /**
     * Toggle an automation active/inactive.
     *
     * @param  int|string  $id
     */
    public function toggleAutomation($id, array $body): Response
    {
        return $this->http->patch(self::WORKERS . "/{$id}", $body);
    }

    /**
     * Get workflows (processes).
     *
     * @param  array  $params  query params (page, list, search, sortBy, sortOrder)
     */
    public function getWorkflows(array $params = []): Response
    {
        return $this->http->get(self::PROCESSES, $params);
    }

    /**
     * Create a workflow.
     */
    public function createWorkflow(array $data): Response
    {
        return $this->http->post(self::PROCESSES, $data);
    }

    /**
     * Update a workflow.
     *
     * @param  int|string  $id
     */
    public function updateWorkflow($id, array $data): Response
    {
        return $this->http->patch(self::PROCESSES . "/{$id}", $data);
    }

    /**
     * Delete a workflow.
     *
     * @param  int|string  $id
     */
    public function deleteWorkflow($id): Response
    {
        return $this->http->delete(self::PROCESSES . "/{$id}");
    }

    /**
     * Toggle a workflow active/inactive.
     *
     * @param  int|string  $id
     */
    public function toggleWorkflow($id, array $body): Response
    {
        return $this->http->patch(self::PROCESSES . "/{$id}", $body);
    }
}
