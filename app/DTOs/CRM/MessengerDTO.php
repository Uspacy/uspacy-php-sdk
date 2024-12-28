<?php

namespace App\DTOs\CRM;

class MessengerDTO
{
    public readonly string $id;

    public readonly string $name;

    public readonly string $link;


    public function __construct(string $id, string $name, string $link)
    {
        $this->id = $id;
        $this->name = $name;
        $this->link = $link;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'link' => $this->link,
        ];
    }
}