<?php

namespace App\DTOs;

class ExternalMetaDTO
{
    /**
     * @var string
     */
    public string $type;

    /**
     * @var string
     */
    public string $value;

    /**
     * @param  string  $type
     * @param  string  $value
     */
    public function __construct(string $type, string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}