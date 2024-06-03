<?php

namespace App\DTOs;


class CrmServiceDTO
{
    /**
     * @var string
     */
    public string $title;

    /**
     * @var int
     */
    public int $owner;

    /**
     * @var string
     */
    public string $source;

    /**
     * @var int
     */
    public int $funnelId;

    /**
     * @var array<CrmServiceEmailDTO>
     */
    public array $email;

    /**
     * @var array<object>
     */
    public array $phone;

    /**
     * @var array<object>
     */
    public array $messengers;

    /**
     * @param  string  $title
     * @param  int  $owner
     * @param  string  $source
     * @param  int  $funnelId
     * @param  array<CrmServiceEmailDTO> $email
     * @param  array<CrmServicePhoneDTO>  $phone
     * @param  array<CrmServiceMessengersDTO>  $messengers
     */
    public function __construct(
        string $title,
        int $owner,
        string $source,
        int $funnelId,
        array $email,
        array $phone,
        array $messengers,
    )
    {
        $this->title = $title;
        $this->owner = $owner;
        $this->source = $source;
        $this->funnelId = $funnelId;
        $this->email = $email;
        $this->phone = $phone;
        $this->messengers = $messengers;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'owner' => $this->owner,
            'source' => $this->source,
            'funnel_id' => $this->funnelId,
            'email' => array_map(fn($email) => $email->toArray(), $this->email),
            'phone' => array_map(fn($phone) => $phone->toArray(), $this->phone),
            'messengers' => array_map(fn($messengers) => $messengers->toArray(), $this->messengers),
        ];
    }
}