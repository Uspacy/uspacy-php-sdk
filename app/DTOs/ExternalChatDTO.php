<?php

namespace App\DTOs;

use Saloon\Http\Response;

class ExternalChatDTO
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var string
     */
    public string $id;

    /**
     * @var string
     */
    public string $pictureUrl;

    /**
     * @var array
     */
    public array $members;

    /**
     * @var array<string>
     */
    public array $externalLines;

    /**
     * @var array<object>
     */
    public array $meta;

    /**
     * @param  string  $name
     * @param  string  $type
     * @param  string  $id
     * @param  string  $pictureUrl
     * @param  array  $members
     * @param  array  $externalLines
     * @param  array  $meta
     */
    public function __construct(
        string $name,
        string $type,
        string $id,
        string $pictureUrl,
        array $members,
        array $externalLines,
        array $meta
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->id = $id;
        $this->pictureUrl = $pictureUrl;
        $this->members = $members;
        $this->externalLines = $externalLines;
        $this->meta = $meta;
    }

    /**
     * @return string|null
     */
    public function getPhoneMeta(): ?string
    {
        foreach ($this->meta as $metaItem) {
            if ($metaItem['type'] === 'phone') {
                return $metaItem['value'];
            }
        }
        return null;
    }

    /**
     * @param  Response  $response
     * @return self
     * @throws \JsonException
     */
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