<?php

namespace App\Http\Integrations\Uspacy\Requests\CrmService;

use App\DTOs\CrmServiceDTO;
use App\Http\Integrations\Uspacy\Enums\EntityTypes;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateCrmEntityItemRequest extends Request  implements HasBody
{
    use HasJsonBody;

    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::POST;

    /**
     * @var EntityTypes
     */
    protected EntityTypes $entityCode;

    /**
     * @var CrmServiceDTO
     */
    protected CrmServiceDTO $serviceDTO;

    public function __construct(EntityTypes $entityCode, CrmServiceDTO $serviceDTO)
    {
        $this->entityCode = $entityCode;
        $this->serviceDTO = $serviceDTO;
    }

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/crm/v1/entities/{$this->entityCode->value}';
    }

    /**
     * @return array
     */
    protected function defaultBody(): array
    {
        return $this->serviceDTO->toArray();
    }
}
