<?php

namespace Uspacy\SDK\Tests\Services;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Uspacy\SDK\DTOs\Collection;
use Uspacy\SDK\DTOs\Crm\DocumentTemplateDTO;
use Uspacy\SDK\DTOs\Crm\DocumentTemplateFieldDTO;
use Uspacy\SDK\DTOs\Crm\RequisiteDTO;
use Uspacy\SDK\DTOs\Crm\RequisiteTemplateDTO;
use Uspacy\SDK\Http\Client\Requests\GetRequest;
use Uspacy\SDK\Http\Client\Requests\PostRequest;
use Uspacy\SDK\Tests\TestCase;

class CrmRequisitesDtoTest extends TestCase
{
    public function test_get_card_requisites_hydrates_collection(): void
    {
        $this->mockGet([
            'data' => [
                ['id' => 1, 'name' => 'Acme LLC', 'is_basic' => true, 'template_id' => 5, 'customfield_1' => 'x'],
            ],
            'meta' => ['total' => 1],
        ]);

        $result = $this->sdk->crmRequisites()->getCardRequisites(['entity_id' => 5]);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertInstanceOf(RequisiteDTO::class, $result->data[0]);
        $this->assertSame('Acme LLC', $result->data[0]->name);
        $this->assertTrue($result->data[0]->isBasic);
        $this->assertSame(5, $result->data[0]->templateId);
        $this->assertSame('x', $result->data[0]->get('customfield_1'));
    }

    public function test_get_templates_hydrates_collection(): void
    {
        $this->mockGet([
            'data' => [['id' => 1, 'name' => 'UA template', 'region' => ['code' => 'UA']]],
            'meta' => ['total' => 1],
        ]);

        $result = $this->sdk->crmRequisites()->getTemplates(['page' => 1]);

        $this->assertInstanceOf(RequisiteTemplateDTO::class, $result->data[0]);
        $this->assertSame('UA template', $result->data[0]->name);
        $this->assertSame(['code' => 'UA'], $result->data[0]->region);
    }

    public function test_bank_requisite_create_returns_requisite_dto(): void
    {
        $this->sdk->withMockClient(new MockClient([
            PostRequest::class => MockResponse::make(['id' => 9, 'name' => 'Bank', 'is_basic' => false], 201),
        ]));

        $requisite = $this->sdk->crmRequisites()->createBankRequisites(1, ['name' => 'Bank']);

        $this->assertInstanceOf(RequisiteDTO::class, $requisite);
        $this->assertSame('Bank', $requisite->name);
    }

    public function test_document_templates_and_fields(): void
    {
        $this->mockGet([
            'data' => [['id' => 1, 'name' => 'Invoice', 'is_active' => true, 'code' => 'inv']],
            'meta' => ['total' => 1],
        ]);
        $templates = $this->sdk->crmDocumentTemplates()->getDocumentTemplates();
        $this->assertInstanceOf(Collection::class, $templates);
        $this->assertInstanceOf(DocumentTemplateDTO::class, $templates->data[0]);
        $this->assertTrue($templates->data[0]->isActive);

        $this->mockGet(['data' => [['id' => 1, 'name' => 'Company name', 'entity' => 'companies', 'symbol_code' => 'company_name']]]);
        $fields = $this->sdk->crmDocumentTemplates()->getDocumentTemplatesFields();
        $this->assertInstanceOf(DocumentTemplateFieldDTO::class, $fields[0]);
        $this->assertSame('company_name', $fields[0]->symbolCode);
        $this->assertSame('companies', $fields[0]->entity);
    }

    public function test_empty_body_does_not_throw(): void
    {
        $this->sdk->withMockClient(new MockClient([
            GetRequest::class => MockResponse::make('', 204),
        ]));

        $this->assertInstanceOf(Collection::class, $this->sdk->crmRequisites()->getCardRequisites());
        $this->assertSame([], $this->sdk->crmDocumentTemplates()->getDocumentTemplatesFields());
    }

    private function mockGet(array $payload): void
    {
        $this->sdk->withMockClient(new MockClient([
            GetRequest::class => MockResponse::make($payload, 200),
        ]));
    }
}
