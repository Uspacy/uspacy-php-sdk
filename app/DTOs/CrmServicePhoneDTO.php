<?php

namespace App\DTOs;


class CrmServicePhoneDTO
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
     * @param  string  $id
     * @param  string  $type
     * @param  string  $value
     */
    public function __construct(
        string $id,
        string $type,
        string $value,
    )
    {
        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
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
        ];
    }
}