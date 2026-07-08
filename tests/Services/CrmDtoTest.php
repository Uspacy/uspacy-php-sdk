<?php

namespace Uspacy\SDK\Tests\Services;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Uspacy\SDK\DTOs\Collection;
use Uspacy\SDK\DTOs\Crm\EntityDTO;
use Uspacy\SDK\DTOs\Crm\FieldDTO;
use Uspacy\SDK\Http\Client\Requests\GetRequest;
use Uspacy\SDK\Http\Client\Requests\PostRequest;
use Uspacy\SDK\Tests\TestCase;

class CrmDtoTest extends TestCase
{
    public function test_get_entities_hydrates_collection_of_entity_dtos(): void
    {
        $this->mockGet([
            'data' => [
                ['id' => 1, 'title' => 'Deal A', 'customfield_1' => 'x'],
                ['id' => 2, 'title' => 'Deal B'],
            ],
            'meta' => ['total' => 2, 'page' => 1],
        ]);

        $result = $this->sdk->crmDeals()->getEntities(['page' => 1]);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertInstanceOf(EntityDTO::class, $result->data[0]);
        $this->assertSame(1, $result->data[0]->id);
        $this->assertSame(2, $result->meta->total);
    }

    public function test_entity_custom_fields_via_get_has(): void
    {
        $this->mockGet(['id' => 5, 'title' => 'Deal', 'customfield_1' => 'value', 'customfield_2' => 99]);

        // create returns a single entity
        $this->sdk->withMockClient(new MockClient([
            PostRequest::class => MockResponse::make(['id' => 5, 'title' => 'Deal', 'customfield_1' => 'value'], 201),
        ]));
        $entity = $this->sdk->crmDeals()->createEntity(['title' => 'Deal']);

        $this->assertInstanceOf(EntityDTO::class, $entity);
        $this->assertSame(5, $entity->id);
        $this->assertTrue($entity->has('customfield_1'));
        $this->assertSame('value', $entity->get('customfield_1'));
        $this->assertSame('Deal', $entity->get('title'));
        $this->assertNull($entity->get('customfield_404'));
    }

    public function test_get_fields_hydrates_field_dtos_from_data_envelope(): void
    {
        $this->mockGet([
            'data' => [
                ['name' => 'Title', 'code' => 'title', 'type' => 'string', 'required' => true, 'system_field' => true],
                ['name' => 'Amount', 'code' => 'amount', 'type' => 'number', 'multiple' => false],
            ],
        ]);

        $fields = $this->sdk->crmDeals()->getFields();

        $this->assertIsArray($fields);
        $this->assertCount(2, $fields);
        $this->assertInstanceOf(FieldDTO::class, $fields[0]);
        $this->assertSame('title', $fields[0]->code);
        $this->assertSame('string', $fields[0]->type);
        $this->assertTrue($fields[0]->required);
        $this->assertTrue($fields[0]->systemField);
    }

    public function test_generic_crm_service_also_returns_dtos(): void
    {
        $this->mockGet([
            'data' => [['id' => 7, 'title' => 'C']],
            'meta' => ['total' => 1],
        ]);

        $result = $this->sdk->crm()->getDeals();
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertSame(7, $result->data[0]->id);
    }

    public function test_empty_body_does_not_throw(): void
    {
        $this->sdk->withMockClient(new MockClient([
            GetRequest::class => MockResponse::make('', 204),
        ]));

        $entity = $this->sdk->crmDeals()->getByStage(3);
        $this->assertInstanceOf(Collection::class, $entity);
        $this->assertSame([], $entity->data);

        $this->assertSame([], $this->sdk->crmDeals()->getFields());
    }

    private function mockGet(array $payload): void
    {
        $this->sdk->withMockClient(new MockClient([
            GetRequest::class => MockResponse::make($payload, 200),
        ]));
    }
}
