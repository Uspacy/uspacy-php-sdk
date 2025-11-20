<?php

namespace Uspacy\SDK\DTOs;

use Saloon\Http\Response;

class ExternalLineDTO
{
    public function __construct(
        public readonly string $id,
        public readonly int $timestamp,
        public readonly string $name,
        public readonly string $icon,
        public readonly string $portal,
        public readonly string $phoneNumber,
        public readonly string $externalId,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return new static(
            id: $data['id'],
            timestamp: $data['timestamp'],
            name: $data['name'],
            icon: $data['icon'],
            portal: $data['portal'],
            phoneNumber: $data['phoneNumber'],
            externalId: $data['externalId'],
        );
    }
}
