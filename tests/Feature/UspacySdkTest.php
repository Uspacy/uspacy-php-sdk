<?php

declare(strict_types=1);

use App\DTOs\CrmServiceDTO;
use App\DTOs\CrmServiceEmailDTO;
use App\DTOs\CrmServiceMessengersDTO;
use App\DTOs\CrmServicePhoneDTO;
use App\DTOs\ExternalLineDTO;
use App\DTOs\RefreshTokenDTO;
use App\Http\Integrations\Uspacy\Enums\EntityTypes;
use App\Http\Integrations\Uspacy\Enums\PhoneTypes;
use App\Http\Integrations\Uspacy\Requests\Auth\RefreshTokenRequest;
use App\Http\Integrations\Uspacy\Requests\CrmService\CreateCrmEntityItemRequest;
use App\Http\Integrations\Uspacy\Requests\Messenger\GetExternalLinesRequest;
use App\Http\Integrations\Uspacy\Requests\TestRequest;
use App\Http\Integrations\Uspacy\UspacySDK;
use Saloon\Enums\Method;
use Saloon\Exceptions\Request\Statuses\UnauthorizedException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Repositories\ArrayStore;
use Tests\TestCase;


uses()
    ->beforeEach(fn() => MockClient::destroyGlobal())
    ->in(__DIR__);

uses(TestCase::class)
    ->in(__DIR__);

test('example', function () {
    expect(true)->toBeTrue();
});

it('basic test', function () {
    $this->assertTrue(true);
});

## Test params ##

$apiUrl = 'https://api.example.com';
$apiToken = 'secret-token';
$value = 'value';

## Test params ##


test('Uspacy SDK can resolve base URL',
    fn() => expect((new UspacySDK($apiUrl, $apiToken))
        ->resolveBaseUrl())->toBe($apiUrl)
);

test('Uspacy SDK can make a GET request', function () use ($apiUrl, $apiToken) {

    $mockClient = new MockClient([
        TestRequest::class => MockResponse::make(body: json_encode(['data' => []]), status: 200),
    ]);

    $sdk = new UspacySDK($apiUrl, $apiToken);
    $sdk->withMockClient($mockClient);

    $request = new TestRequest(Method::GET);

    $sdk->send($request);

    $mockClient->assertSent(function (TestRequest $sendRequest) {

        return $sendRequest->getMethod() === Method::GET;
    });

});


test('Uspacy SDK can make a POST request', function () use ($apiUrl, $apiToken, $value) {

    $mockClient = new MockClient([
        TestRequest::class => MockResponse::make(body: json_encode(['data' => []]), status: 201),
    ]);

    $sdk = new UspacySDK($apiUrl, $apiToken);
    $sdk->withMockClient($mockClient);

    $request = new TestRequest(Method::POST, $value);

    $sdk->send($request);

    $mockClient->assertSent(function (TestRequest $sendRequest) use ($value) {
        return $sendRequest->body()->all() === ['value' => $value];
    });

});

test('Uspacy SDK can make a Refresh token', function () use ($apiUrl, $apiToken) {
    $mockClient = new MockClient([
        RefreshTokenRequest::class => MockResponse::make(body: json_encode([
            'data' => [
                'expireInSeconds' => 720,
                'jwt' => 'some-jwt-token',
                'refreshToken' => 'some-refresh-token'
            ]
        ]),
            status: 200),
    ]);

    $sdk = new UspacySDK($apiUrl, $apiToken);
    $sdk->withMockClient($mockClient);

    $response = $sdk->send(new RefreshTokenRequest());

    $data = $response->json()['data'];

    $refreshTokenDTO = new RefreshTokenDTO(
        $data['expireInSeconds'],
        $data['jwt'],
        $data['refreshToken']
    );

    expect($refreshTokenDTO->expireInSeconds)->toBe(720)
        ->and($refreshTokenDTO->jwt)->toBe('some-jwt-token')
        ->and($refreshTokenDTO->refreshToken)->toBe('some-refresh-token');
});

test('Uspacy SDK can make a get External line', function () use ($apiUrl, $apiToken) {
    $mockClient = new MockClient([
        GetExternalLinesRequest::class => MockResponse::make(body: json_encode([
            'data' => [
                "timestamp" => 1620211200000,
                "name" => "Main line",
                "icon" => "https://example.com/icon.png",
                "portal" => "portal",
                "phoneNumber" => "+1234567890",
                "externalId" => "externalId",
                "id" => "659ff121fd62e43063340f2f",
            ]
        ]),
            status: 200),
    ]);

    $sdk = new UspacySDK($apiUrl, $apiToken);
    $sdk->withMockClient($mockClient);

    $response = $sdk->send(new GetExternalLinesRequest());

    $data = $response->json()['data'];

    $refreshTokenDTO = new ExternalLineDTO(
        $data['timestamp'],
        $data['name'],
        $data['icon'],
        $data['portal'],
        $data['phoneNumber'],
        $data['externalId'],
        $data['id'],
    );
    expect($refreshTokenDTO->id)->toBe('659ff121fd62e43063340f2f')
        ->and($refreshTokenDTO->name)->toBe('Main line')
        ->and($refreshTokenDTO->icon)->toBe('https://example.com/icon.png')
        ->and($refreshTokenDTO->externalId)->toBe('externalId')
        ->and($refreshTokenDTO->timestamp)->toBe(1620211200000)
        ->and($refreshTokenDTO->portal)->toBe('portal')
        ->and($refreshTokenDTO->phoneNumber)->toBe('+1234567890');
});

test('Uspacy SDK can create a CRM entity (Lead)', function () use ($apiUrl, $apiToken) {

    $mockClient = new MockClient([
        MockResponse::fixture('/uspacy/createEntityItem')
    ]);

    $sdk = new UspacySDK($apiUrl, $apiToken);
    $sdk->withMockClient($mockClient);


    $entityType = EntityTypes::LEAD;
    $phoneType = PhoneTypes::WORK;

    $email1 = new CrmServiceEmailDTO(
        '01gdnpddf5xgzxqvcn4x83mc2s',
        'main',
        'stark@gmail.com',
        true
    );

    $email2 = new CrmServiceEmailDTO(
        '01gdnpddf5xgzxqvcn4x83mc3s',
        'work',
        'benner@gmail.com',
        false
    );
    $phone = new CrmServicePhoneDTO(
        '01gdnpddf5xgzxqvcn4x83mc3s',
        $phoneType->value,
        '+380123456789'
    );

    $messengers = new CrmServiceMessengersDTO(
        '01gdnpddf5xgzxqvcn4x83mc3s',
        'facebook',
        'https://www.facebook.com'
    );

    $dto = new CrmServiceDTO(
        'Lead example',
        1,
        'shop',
        5,
        [$email1, $email2],
        [$phone],
        [$messengers],
    );

    $request = new CreateCrmEntityItemRequest($entityType, $dto);

    $response = $sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($response->headers())->toBeInstanceOf(ArrayStore::class)
        ->and($response->headers())->get('Content-type')->toEqual('aplication/json')
        ->and($response->array())->toBe(
            [
                "title" => "Lead example",
                "owner" => 1,
                "created_by" => 1,
                "changed_by" => 1,
                "converted" => false,
                "first_name" => "Johnny",
                "last_name" => "Stark",
                "patronymic" => "",
                "company_name" => "",
                "position" => "Owner",
                "utm_source" => "",
                "utm_medium" => "",
                "utm_campaign" => "",
                "utm_content" => "",
                "utm_term" => "",
                "source" => [
                    [
                        "title" => "Shop",
                        "value" => "shop",
                        "color" => "",
                        "sort" => 10,
                        "selected" => true
                    ],
                    [
                        "title" => "Site",
                        "value" => "site",
                        "color" => "",
                        "sort" => 20,
                        "selected" => false
                    ]
                ],
                "email" => [
                    [
                        "id" => "",
                        "type" => "main",
                        "value" => "stark@gmail.com",
                        "main" => true
                    ],
                    [
                        "id" => "01gdnpddf5xgzxqvcn4x83mc2s",
                        "type" => "work",
                        "value" => "benner@gmail.com",
                        "main" => false
                    ]
                ],
                "phone" => [
                    [
                        "id" => "01gdnpddf5xgzxqvcn4x83mc2s",
                        "type" => "WORK",
                        "value" => "+380123456789"
                    ]
                ],
                "messengers" => [
                    [
                        "id" => "01gdnpddf5xgzxqvcn4x83bn2s",
                        "name" => "facebook",
                        "link" => "https://www.facebook.com"
                    ]
                ],
                "kanban_status" => "IN_WORK",
                "kanban_reason_id" => 11,
                "kanban_stage_id" => 22,
                "id" => 12,
                "created_at" => 12156465,
                "updated_at" => 12156465
            ]);
});

test('Uspacy SDK encounters error while creating a CRM entity (Lead)', function () use ($apiUrl, $apiToken) {
    // Mocking the HTTP client to return an error response
    $mockClient = new MockClient([
            MockResponse::fixture('/uspacy/error')
        ]
    );

    // Creating an instance of the SDK with the mock client
    $sdk = new UspacySDK($apiUrl, $apiToken);
    $sdk->withMockClient($mockClient);

    // Creating the DTO for the CRM entity
    $entityType = EntityTypes::LEAD;
    $phoneType = PhoneTypes::WORK;
    $email = new CrmServiceEmailDTO(
        '01gdnpddf5xgzxqvcn4x83mc2s',
        'main',
        'stark@gmail.com',
        true
    );
    $phone = new CrmServicePhoneDTO(
        '01gdnpddf5xgzxqvcn4x83mc3s',
        $phoneType->value,
        '+380123456789'
    );
    $messengers = new CrmServiceMessengersDTO(
        '01gdnpddf5xgzxqvcn4x83mc3s',
        'facebook',
        'https://www.facebook.com'
    );
    $dto = new CrmServiceDTO(
        'Lead example',
        1,
        'shop',
        5,
        [$email],
        [$phone],
        [$messengers]
    );
    $request = new CreateCrmEntityItemRequest($entityType, $dto);

    try {
        $sdk->send($request);
    } catch (UnauthorizedException $e) {
        expect($e->getStatus())->toBe(401)
            ->and($e->getMessage())->toEqual('Unauthorized (401) Response: {"error": "Unauthorized"}');
        return;
    }
});





