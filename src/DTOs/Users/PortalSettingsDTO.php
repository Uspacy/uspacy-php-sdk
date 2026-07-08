<?php

namespace Uspacy\SDK\DTOs\Users;

/**
 * A user's portal settings (mirrors the JS `IPortalSettings`).
 *
 * The documented fields are typed; the full payload is retained in {@see $raw}.
 */
final class PortalSettingsDTO
{
    /**
     * @param  array<int, string>  $availableCurrencies
     * @param  array<int, string>  $weekends
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?string $nameFormat,
        public readonly ?string $lang,
        public readonly ?string $timeFormat,
        public readonly ?string $dateFormat,
        public readonly ?string $timezone,
        public readonly ?string $firstDay,
        public readonly ?string $country,
        public readonly ?string $phoneFormat,
        public readonly array $availableCurrencies,
        public readonly ?string $defaultCurrency,
        public readonly array $weekends,
        public readonly ?string $workhoursMode,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            nameFormat: $data['nameFormat'] ?? null,
            lang: $data['lang'] ?? null,
            timeFormat: $data['timeFormat'] ?? null,
            dateFormat: $data['dateFormat'] ?? null,
            timezone: $data['timezone'] ?? null,
            firstDay: $data['firstDay'] ?? null,
            country: $data['country'] ?? null,
            phoneFormat: $data['phoneFormat'] ?? null,
            availableCurrencies: $data['availableCurrencies'] ?? [],
            defaultCurrency: $data['defaultCurrency'] ?? null,
            weekends: $data['weekends'] ?? [],
            workhoursMode: $data['workhoursMode'] ?? null,
            raw: $data,
        );
    }
}
