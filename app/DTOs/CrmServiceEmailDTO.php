<?php

namespace App\DTOs;


class CrmServiceEmailDTO
{
    /**
     * @var string
     */
    public string $id;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var string
     */
    public string $value;

    /**
     * @var bool
     */
    public bool $main;

    /**
     * @param  string  $id
     * @param  string  $type
     * @param  string  $value
     * @param  bool  $main
     */
    public function __construct(
        string $id,
        string $type,
        string $value,
        bool $main,
    )
    {
        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
        $this->main = $main;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'value' => $this->value,
            'main' => $this->main,
        ];
    }
}