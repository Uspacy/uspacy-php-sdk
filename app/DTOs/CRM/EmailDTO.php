<?php

namespace App\DTOs\CRM;

class EmailDTO
{
    public readonly string $id;

    public readonly string $type;

    public readonly string $value;

    public readonly bool $main;

    public function __construct(string $id, string $type, string $value, bool $main)
    {
        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
        $this->main = $main;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'value' => $this->value,
            'main' => $this->main
        ];
    }
}