<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\Http\Client\Requests\Settings\GetGeneralSettingsRequest;

/**
 * Settings service.
 *
 * Covers the `settings/v1` module.
 */
class SettingsService extends Service
{
    /**
     * Get general portal settings for a domain.
     */
    public function getGeneralSettings(string $domain): Response
    {
        return $this->http->connector()->send(new GetGeneralSettingsRequest($domain));
    }
}
