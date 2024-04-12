<?php

namespace App\Mappers\CRM;

use App\DTOs\CRM\EntityItemDTO;

class EntityItemDTOToArrayMapper
{
    public function map(EntityItemDTO $entityItemDTO): array
    {
        return [
            'title'      => $entityItemDTO->title,
            'owner'      => $entityItemDTO->owner,
            'source'     => $entityItemDTO->source,
            'funnel_id'  => $entityItemDTO->funnel_id,
            'email'      => array_map(fn($email) => $email->toArray(), $entityItemDTO->emails),
            'phone'      => array_map(fn($phone) => $phone->toArray(), $entityItemDTO->phones),
            'messengers' => array_map(fn($messenger) => $messenger->toArray(), $entityItemDTO->messengers),
        ];
    }
}