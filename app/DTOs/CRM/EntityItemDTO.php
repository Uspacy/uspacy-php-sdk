<?php

namespace App\DTOs\CRM;

class EntityItemDTO
{
    public readonly string $title;

    public readonly int $owner;

    public readonly string $source;

    public readonly int $funnel_id;

    /** @var EmailDTO[] */
    public readonly array $emails;

    /** @var PhoneDTO[] */
    public readonly array $phones;

    /** @var MessengerDTO[] */
    public readonly array $messengers;

    /**
     * @param EmailDTO[] $emails
     * @param PhoneDTO[] $phones
     */
    public function __construct(
        string $title,
        int $owner,
        string $source,
        int $funnel_id,
        array $emails,
        array $phones,
        array $messengers
    ) {
        $this->title = $title;
        $this->owner = $owner;
        $this->source = $source;
        $this->funnel_id = $funnel_id;
        $this->emails = $emails;
        $this->phones = $phones;
        $this->messengers = $messengers;
    }
}