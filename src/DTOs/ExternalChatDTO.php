<?php

namespace Uspacy\SDK\DTOs;

use Saloon\Contracts\Response;

class ExternalChatDTO
{
    public string $name;
    public string $type;
    public string $id;
    public string $pictureUrl;
    public array $members;
    public array $externalLines;
    public array $meta;

    public function __construct(
        string $name,
        string $type,
        string $id,
        string $pictureUrl,
        array $members,
        array $externalLines,
        array $meta
        )
    {
        $this->name = $name;
        $this->type = $type;
        $this->id = $id;
        $this->pictureUrl = $pictureUrl;
        $this->members = $members;
        $this->externalLines = $externalLines;
        $this->meta = $meta;
    }

    public function getPhoneMeta(): ?string
    {
        foreach ($this->meta as $metaItem) {
            if ($metaItem['type'] === 'phone') {
                return $metaItem['value'];
            }
        }

        return null;
    }

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return new static(
            $data['name'],
            $data['type'],
            $data['id'],
            $data['pictureUrl'],
            $data['members'],
            $data['externalLines'],
            $data['meta']
        );
    }
}
