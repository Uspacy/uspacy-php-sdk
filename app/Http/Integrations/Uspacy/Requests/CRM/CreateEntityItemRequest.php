<?php

namespace App\Http\Integrations\Uspacy\Requests\CRM;

use App\DTOs\CRM\EntityItemDTO;
use App\Http\Integrations\Uspacy\Enums\CRMEntityType;
use App\Mappers\CRM\CreateEntityItemResponseToDTOMapper;
use App\Mappers\CRM\EntityItemDTOToArrayMapper;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateEntityItemRequest extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::POST;

    public function __construct(
        protected readonly CRMEntityType $entity,
        protected readonly EntityItemDTO $entityItem
    ) {
    }

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return "/entities/entity/{$this->entity->value}";
    }

    protected function defaultBody(): array
    {
        return (new EntityItemDTOToArrayMapper())->map($this->entityItem);
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $data = $response->json();
        $mapper = new CreateEntityItemResponseToDTOMapper();

        return $mapper->map($data);
    }
}