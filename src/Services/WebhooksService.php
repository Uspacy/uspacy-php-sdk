<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Webhooks service.
 *
 * Mirrors the JS SDK's WebhooksService. Each method targets either the outgoing
 * (`/company/v1/webhooks`) or incoming (`/company/v1/incoming_webhooks`)
 * namespace, selected by the `$isIncoming` flag.
 */
class WebhooksService extends Service
{
    private const OUTGOING = '/company/v1/webhooks';

    private const INCOMING = '/company/v1/incoming_webhooks';

    /**
     * Get a page of webhooks.
     */
    public function getWebhooks(int $page = 1, ?int $list = null, ?string $name = null, bool $isIncoming = false): Response
    {
        return $this->http->get($this->namespace($isIncoming), [
            'page' => $page,
            'list' => $list,
            'name' => $name,
        ]);
    }

    /**
     * Get a webhook by id.
     *
     * @param  int|string  $id
     */
    public function getWebhookById($id, bool $isIncoming = false): Response
    {
        return $this->http->get($this->namespace($isIncoming) . "/{$id}/");
    }

    /**
     * Create a webhook.
     */
    public function createWebhook(array $body, bool $isIncoming = false): Response
    {
        return $this->http->post($this->namespace($isIncoming), $body);
    }

    /**
     * Update a webhook.
     *
     * @param  int|string  $id
     */
    public function updateWebhook($id, array $body, bool $isIncoming = false): Response
    {
        return $this->http->patch($this->namespace($isIncoming) . "/{$id}", $body);
    }

    /**
     * Delete a webhook.
     *
     * @param  int|string  $id
     */
    public function deleteWebhook($id, bool $isIncoming = false): Response
    {
        return $this->http->delete($this->namespace($isIncoming) . "/{$id}/");
    }

    /**
     * Delete several webhooks at once.
     *
     * @param  array  $ids  webhook ids
     */
    public function deleteSelectedWebhooks(array $ids, bool $isIncoming = false): Response
    {
        return $this->http->delete($this->namespace($isIncoming), ['ids' => $ids]);
    }

    /**
     * Toggle a webhook active/inactive.
     *
     * @param  int|string  $id
     */
    public function toggleWebhook($id, bool $isIncoming = false): Response
    {
        return $this->http->patch($this->namespace($isIncoming) . "/{$id}/toggle");
    }

    /**
     * Repeat (re-fire) a webhook.
     *
     * @param  int|string  $id
     */
    public function repeatWebhook($id, bool $isIncoming = false): Response
    {
        return $this->http->patch($this->namespace($isIncoming) . "/{$id}/repeat");
    }

    private function namespace(bool $isIncoming): string
    {
        return $isIncoming ? self::INCOMING : self::OUTGOING;
    }
}
