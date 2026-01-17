<?php

namespace App\DTOs\CRM;

class SourceDTO
{
    public readonly string $title;

    public readonly string $value;

    public readonly string $color;

    public readonly int $sort;

    public readonly bool $selected;

    public function __construct(string $title, string $value, string $color, int $sort, bool $selected)
    {
        $this->title = $title;
        $this->value = $value;
        $this->color = $color;
        $this->sort = $sort;
        $this->selected = $selected;
    }
}