<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Migrations service (import status & control).
 *
 * Mirrors the status/control surface of the JS SDK's MigrationsService — import
 * progress, data presence and stopping imports. The per-system entity
 * calculate/import methods (bitrix24, amo, etc.) are intentionally omitted: in
 * the JS SDK they branch across several ad-hoc gateway paths per system, which
 * does not port cleanly to a single-base-URL server SDK.
 */
class MigrationsService extends Service
{
    private const IMPORT = '/import';

    /**
     * Get import status for all systems.
     */
    public function getAllSystemsStatus(): Response
    {
        return $this->http->get(self::IMPORT . '/progress');
    }

    /**
     * Get import progress for a system.
     */
    public function getSystemProgress(string $system): Response
    {
        return $this->http->get(self::IMPORT . '/progress', ['system' => $system]);
    }

    /**
     * Get Monday.com import progress for a system.
     */
    public function getMondayProgress(string $system): Response
    {
        return $this->http->get("/progress/{$system}");
    }

    /**
     * Get data presence (Zoho).
     */
    public function getDataPresence(): Response
    {
        return $this->http->post('/dataPresence/zoho');
    }

    /**
     * Stop a running import for a system.
     */
    public function stopImport(string $system): Response
    {
        if ($system === 'trello') {
            return $this->http->post(self::IMPORT . '/v1/trello/stop');
        }

        return $this->http->get(self::IMPORT . '/stop', ['system' => $system]);
    }
}
