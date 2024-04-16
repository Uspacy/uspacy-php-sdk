<?php

namespace App\DTOs;


class CrmServiceMessengersDTO
{
    /**
     * @var string
     */
    public string $id;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $link;

    /**
     * @param  string  $id
     * @param  string  $name
     * @param  string  $link
     */
    public function __construct(
        string $id,
        string $name,
        string $link,
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->link = $link;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'link' => $this->link,
        ];
    }
}