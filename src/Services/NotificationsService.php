<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Notifications service.
 *
 * Mirrors the JS SDK's NotificationsService (`/notifications/v1/notifications`).
 */
class NotificationsService extends Service
{
    private const NAMESPACE = '/notifications/v1/notifications';

    /**
     * Get the current user's notifications.
     */
    public function getNotifications(): Response
    {
        return $this->http->get(self::NAMESPACE);
    }
}
