<?php

namespace App\DTOs\CRM;

class UtmDTO
{
    public readonly string $source;

    public readonly string $medium;

    public readonly string $campaign;

    public readonly string $content;

    public readonly string $term;

    public function __construct(string $source, string $medium, string $campaign, string $content, string $term)
    {
        $this->source = $source;
        $this->medium = $medium;
        $this->campaign = $campaign;
        $this->content = $content;
        $this->term = $term;
    }
}