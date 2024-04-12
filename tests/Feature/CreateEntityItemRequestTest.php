<?php

namespace Tests\Feature;

use App\DTOs\CRM\EmailDTO;
use App\DTOs\CRM\EntityItemDTO;
use App\DTOs\CRM\ExternalEntityItemDTO;
use App\DTOs\CRM\MessengerDTO;
use App\DTOs\CRM\PhoneDTO;
use App\Http\Integrations\Uspacy\Enums\CRMEntityType;
use App\Http\Integrations\Uspacy\Enums\PhoneType;
use App\Http\Integrations\Uspacy\Requests\CRM\CreateEntityItemRequest;
use App\Http\Integrations\Uspacy\UspacySDK;
use PHPUnit\Framework\TestCase;
use Saloon\Exceptions\Request\Statuses\UnauthorizedException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class CreateEntityItemRequestTest extends TestCase
{

    protected readonly EntityItemDTO $entityItem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityItem = new EntityItemDTO(
            'testTitle',
            32,
            'testSource',
            2,
            [
                new EmailDTO(23, 'testType', 'testemail23@gmail.com', true),
                new EmailDTO(20, 'testType1', 'test-email23@gmail.com', false),
                new EmailDTO(8, 'testType2', 'test.email23@gmail.com', false)
            ],
            [
                new PhoneDTO(3, PhoneType::MOBILE, 'testValue'),
            ],
            [
                new MessengerDTO(12, 'facebook', 'https://www.facebook.com/?locale=uk_UA'),
                new MessengerDTO(1, 'instagram', 'https://www.instagram.com/')
            ]
        );
    }

    public function test_unauthorized()
    {
        $mockClient = new MockClient([
           CreateEntityItemRequest::class => MockResponse::make(['error' => 'Unauthorized.'], 401)
        ]);

        $connector =  new UspacySDK('https://domain.uspacy.ua/crm/v1','JHDpfje08080dfklJsf3');
        $connector->withMockClient($mockClient);

        $request = new CreateEntityItemRequest(CRMEntityType::DEAL, $this->entityItem);

        $this->expectException(UnauthorizedException::class);

        $connector->send($request);
    }

    public function test_created()
    {
        $mockClient = new MockClient([
            CreateEntityItemRequest::class => MockResponse::make([
                'title' => 'Sample Title',
                'owner' => rand(1, 100),
                'created_by' => rand(1, 100),
                'changed_by' => rand(1, 100),
                'converted' => (bool)rand(0, 1),
                'first_name' => 'John',
                'last_name' => 'Doe',
                'patronymic' => 'Smith',
                'company_name' => 'ABC Company',
                'position' => 'Manager',
                'utm_source' => 'Google',
                'utm_medium' => 'CPC',
                'utm_campaign' => 'Summer Sale',
                'utm_content' => 'Banner',
                'utm_term' => 'keyword',
                'source' => [
                    'title' => 'Sample Source',
                    'value' => 'Sample Value',
                    'color' => 'blue',
                    'sort' => rand(1, 10),
                    'selected' => (bool)rand(0, 1)
                ],
                'email' => [
                    [
                        'id' => '1',
                        'type' => 'work',
                        'value' => 'john.doe@example.com',
                        'main' => true
                    ],
                    [
                        'id' => '2',
                        'type' => 'home',
                        'value' => 'john.doe@home.com',
                        'main' => false
                    ]
                ],
                'phone' => [
                    [
                        'id' => '1',
                        'type' => PhoneType::WORK->value,
                        'value' => '123456789',
                    ],
                    [
                        'id' => '2',
                        'type' => PhoneType::HOME->value,
                        'value' => '987654321',
                    ],
                    [
                        'id' => '3',
                        'type' => PhoneType::MOBILE->value,
                        'value' => '456789123',
                    ]
                ],
                'messengers' => [
                    [
                        'id' => '1',
                        'name' => 'WhatsApp',
                        'link' => 'https://wa.me/123456789',
                    ],
                    [
                        'id' => '2',
                        'name' => 'Telegram',
                        'link' => 'https://t.me/johndoe',
                    ]
                ],
                'kanban_status' => 'Pending',
                'kanban_reason_id' => rand(1, 10),
                'kanban_stage_id' => rand(1, 5),
                'id' => rand(1000, 9999),
                'created_at' => time(),
                'updated_at' => time()
            ], 201)
        ]);

        $connector =  new UspacySDK('https://domain.uspacy.ua/crm/v1','JHDpfje08080dfklJsf3');
        $connector->withMockClient($mockClient);

        $request = new CreateEntityItemRequest(CRMEntityType::DEAL, $this->entityItem);

        $response = $connector->send($request);

        $this->assertInstanceOf(ExternalEntityItemDTO::class, $response->dto());
    }
}
