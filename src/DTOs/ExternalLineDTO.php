<?php

namespace Uspacy\SDK\DTOs;

use Saloon\Contracts\Response;

class ExternalLineDTO
{
    public $timestamp;
    public $name;
    public $icon;
    public $portal;
    public $phoneNumber;
    public $externalId;
    public $id;

    public function __construct($timestamp, $name, $icon, $portal, $phoneNumber, $externalId, $id)
    {
        $this->timestamp = $timestamp;
        $this->name = $name;
        $this->icon = $icon;
        $this->portal = $portal;
        $this->phoneNumber = $phoneNumber;
        $this->externalId = $externalId;
        $this->id = $id;
    }

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return new static($data['timestamp'], $data['name'], $data['icon'], $data['portal'], $data['phoneNumber'], $data['externalId'], $data['id']);
    }
}
