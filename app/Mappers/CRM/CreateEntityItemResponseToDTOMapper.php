<?php

namespace App\Mappers\CRM;

use App\DTOs\CRM\EmailDTO;
use App\DTOs\CRM\ExternalEntityItemDTO;
use App\DTOs\CRM\KanbanDTO;
use App\DTOs\CRM\MessengerDTO;
use App\DTOs\CRM\PhoneDTO;
use App\DTOs\CRM\SourceDTO;
use App\DTOs\CRM\UtmDTO;
use App\Http\Integrations\Uspacy\Enums\PhoneType;

class CreateEntityItemResponseToDTOMapper
{
    public function map(array $data): ExternalEntityItemDTO
    {
        return new ExternalEntityItemDTO(
            $data['id'],
            $data['title'],
            $data['owner'],
            $data['created_by'],
            $data['changed_by'],
            $data['converted'],
            $data['first_name'],
            $data['last_name'],
            $data['patronymic'],
            $data['company_name'],
            $data['position'],
            new UtmDTO(
                $data['utm_source'],
                $data['utm_medium'],
                $data['utm_campaign'],
                $data['utm_content'],
                $data['utm_term']
            ),
            new SourceDTO(
                $data['source']['title'],
                $data['source']['value'],
                $data['source']['color'],
                $data['source']['sort'],
                $data['source']['selected']
            ),
            new KanbanDTO(
                $data['kanban_status'],
                $data['kanban_reason_id'],
                $data['kanban_stage_id']
            ),
            $this->mapEmailDTOs($data['email']),
            $this->mapPhoneDTOs($data['phone']),
            $this->mapMessengerDTOs($data['messengers']),
            (new \DateTimeImmutable())->setTimestamp(($data['created_at'])),
            (new \DateTimeImmutable())->setTimestamp(($data['updated_at'])),
        );
    }

    private function mapEmailDTOs(array $emails): array
    {
        $emailDTOs = [];
        foreach ($emails as $email) {
            $emailDTOs[] = new EmailDTO(
                $email['id'],
                $email['type'],
                $email['value'],
                $email['main']
            );
        }

        return $emailDTOs;
    }

    private function mapPhoneDTOs(array $phones): array
    {
        $phoneDTOs = [];
        foreach ($phones as $phone) {
            $phoneDTOs[] = new PhoneDTO(
                $phone['id'],
                PhoneType::from($phone['type']),
                $phone['value']
            );
        }
        return $phoneDTOs;
    }

    private function mapMessengerDTOs(array $messengers): array
    {
        $messengerDTOs = [];
        foreach ($messengers as $messenger) {
            $messengerDTOs[] = new MessengerDTO(
                $messenger['id'],
                $messenger['name'],
                $messenger['link']
            );
        }
        return $messengerDTOs;
    }
}