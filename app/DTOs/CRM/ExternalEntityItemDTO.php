<?php

namespace App\DTOs\CRM;

class ExternalEntityItemDTO
{

    public readonly int $id;

    public readonly string $title;

    public readonly int $owner;

    public readonly int $createdBy;

    public readonly int $changedBy;

    public readonly bool $converted;

    public readonly string $firstName;

    public readonly string $lastName;

    public readonly string $patronymic;

    public readonly string $companyName;

    public readonly string $position;

    public readonly UtmDTO $utm;

    public readonly SourceDTO $source;

    public readonly KanbanDTO $kanban;

    /** @var EmailDTO[] */
    public readonly array $emails;

    /** @var PhoneDTO[] */
    public readonly array $phones;

    /** @var MessengerDTO[] */
    public readonly array $messengers;

    public readonly \DateTimeImmutable $created_at;

    public readonly \DateTimeImmutable $updated_at;

    /**
     * @param EmailDTO[] $emails
     * @param PhoneDTO[] $phones
     */
    public function __construct(
        int $id,
        string $title,
        int $owner,
        int $createdBy,
        int $changedBy,
        bool $converted,
        string $firstName,
        string $lastName,
        string $patronymic,
        string $companyName,
        string $position,
        UtmDTO $utm,
        SourceDTO $source,
        KanbanDTO $kanban,
        array $emails,
        array $phones,
        array $messengers,
        \DateTimeImmutable $created_at,
        \DateTimeImmutable $updated_at
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->owner = $owner;
        $this->createdBy = $createdBy;
        $this->changedBy = $changedBy;
        $this->converted = $converted;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->patronymic = $patronymic;
        $this->companyName = $companyName;
        $this->position = $position;
        $this->utm = $utm;
        $this->source = $source;
        $this->kanban = $kanban;
        $this->emails = $emails;
        $this->phones = $phones;
        $this->messengers = $messengers;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

}