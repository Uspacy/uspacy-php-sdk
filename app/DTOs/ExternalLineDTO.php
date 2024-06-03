<?php

namespace App\DTOs;

use Saloon\Http\Response;

class ExternalLineDTO
{
    /**
     * @var int
     */
    public int $timestamp;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $icon;

    /**
     * @var string
     */
    public string $portal;

    /**
     * @var string
     */
    public string $phoneNumber;

    /**
     * @var string
     */
    public string $externalId;

    /**
     * @var string
     */
    public string $id;

    /**
     * @param  int  $timestamp
     * @param  string  $name
     * @param  string  $icon
     * @param  string  $portal
     * @param  string  $phoneNumber
     * @param  string  $externalId
     * @param  string  $id
     */
    public function __construct(
        int $timestamp,
        string $name,
        string $icon,
        string $portal,
        string $phoneNumber,
        string $externalId,
        string $id
    ) {
        $this->timestamp = $timestamp;
        $this->name = $name;
        $this->icon = $icon;
        $this->portal = $portal;
        $this->phoneNumber = $phoneNumber;
        $this->externalId = $externalId;
        $this->id = $id;
    }

    /**
     * @param  Response  $response
     * @return self
     * @throws \JsonException
     */
    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return new static($data['timestamp'], $data['name'], $data['icon'], $data['portal'], $data['phoneNumber'],
            $data['externalId'], $data['id']);
    }
}
