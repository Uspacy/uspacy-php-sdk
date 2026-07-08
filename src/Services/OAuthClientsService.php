<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * OAuth clients service.
 *
 * Mirrors the JS SDK's OAuthClientsService (`/company/v1/oauth_clients`).
 */
class OAuthClientsService extends Service
{
    private const NAMESPACE = '/company/v1/oauth_clients';

    /**
     * Get all OAuth clients.
     */
    public function getOAuthClients(): Response
    {
        return $this->http->get(self::NAMESPACE);
    }

    /**
     * Create an OAuth client.
     */
    public function createOAuthClient(array $body): Response
    {
        return $this->http->post(self::NAMESPACE, $body);
    }

    /**
     * Update an OAuth client.
     *
     * @param  int|string  $id
     */
    public function updateOAuthClient($id, array $body): Response
    {
        return $this->http->patch(self::NAMESPACE . "/{$id}", $body);
    }

    /**
     * Delete an OAuth client.
     *
     * @param  int|string  $id
     */
    public function deleteOAuthClient($id): Response
    {
        return $this->http->delete(self::NAMESPACE . "/{$id}");
    }

    /**
     * Get the permissions available to OAuth clients.
     */
    public function getAvailablePermissions(): Response
    {
        return $this->http->get(self::NAMESPACE . '/available_permissions');
    }
}
