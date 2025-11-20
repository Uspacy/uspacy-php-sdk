<?php

namespace Uspacy\SDK\DTOs;

use DateTime;
use Saloon\Http\Response;

class ExternalLineDTO
{
    public function __construct(
        public readonly string $id,
        public readonly \DateTimeInterface $timestamp,
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
            timestamp: DateTime::createFromFormat('U', $data['timestamp']),
            name: $data['name'],
            icon: $data['icon'],
            portal: $data['portal'],
            phoneNumber: $data['phoneNumber'],
            externalId: $data['externalId'],
        );
    }
}
