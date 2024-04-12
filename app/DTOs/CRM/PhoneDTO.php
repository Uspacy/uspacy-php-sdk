<?php

namespace App\DTOs\CRM;

use App\Http\Integrations\Uspacy\Enums\PhoneType;

class PhoneDTO
{
    public readonly string $id;

    public readonly PhoneType $type;

    public readonly string $value;


    public function __construct(string $id, PhoneType $type, string $value)
    {
        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type->value,
            'value' => $this->value,
        ];
    }
}