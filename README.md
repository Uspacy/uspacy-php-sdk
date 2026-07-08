# Uspacy PHP SDK

PHP SDK for [Uspacy](https://uspacy.com) – a single workspace for managing key processes of your organization with a focus on results. Communication, collaboration and CRM. All-in-one.

Built on [Saloon](https://docs.saloon.dev/) and designed to mirror the official
[JS](https://github.com/Uspacy/uspacy-js-sdk) and [Go](https://github.com/Uspacy/uspacy-go-sdk) SDKs.
See the [Uspacy API reference](https://uspacy.readme.io/reference/introduction) for endpoint details.

## Requirements

- PHP `^8.2`
- Laravel `^11 || ^12 || ^13` (the SDK ships as a Laravel package)

## Installation

```bash
composer require uspacy/uspacy-php-sdk
```

The service provider is auto-discovered. Publish the config if you want to tune retries:

```bash
php artisan vendor:publish --provider="Uspacy\SDK\UspacySDKServiceProvider"
```

## Quick start

```php
use Uspacy\SDK\Http\Client\UspacySDK;

// Base URL is your portal host, e.g. https://<domain>.uspacy.ua
$sdk = new UspacySDK('https://acme.uspacy.ua', $accessToken);

// Every call returns a Saloon\Http\Response — use ->json(), ->dto(), ->status()
$deals = $sdk->crm()->getDeals(['page' => 1, 'list' => 20])->json();
```

## Architecture

The SDK exposes **service facades** on the connector, mirroring the JS SDK's
`httpClient.client.get/post/...` design:

- `UspacySDK` — the Saloon connector (auth, retries, base URL).
- `HttpClient` — a thin verb-oriented wrapper (`get/post/patch/put/delete/postForm`).
- Generic request classes (`GetRequest`, `PostRequest`, …) build the actual HTTP calls.
- One `*Service` per domain, each owning its API namespace.

Every service is reachable through an accessor on the connector:

| Accessor | Service | Module |
| --- | --- | --- |
| `$sdk->crm()` | `CrmService` | `crm/v1` |
| `$sdk->smartObjects()` | `SmartObjectsService` | `crm/v1` |
| `$sdk->tasks()` | `TasksService` | `tasks/v1` |
| `$sdk->activities()` | `ActivitiesService` | `activities/v1` |
| `$sdk->users()` | `UsersService` | `company/v1` |
| `$sdk->departments()` | `DepartmentsService` | `company/v1` |
| `$sdk->groups()` | `GroupsService` | `groups/v1` |
| `$sdk->comments()` | `CommentsService` | `comments/v1` |
| `$sdk->newsFeed()` | `NewsFeedService` | `newsfeed/v1` |
| `$sdk->emails()` | `EmailsService` | `email/v1` |
| `$sdk->files()` | `FilesService` | `files/v1` |
| `$sdk->messenger()` | `MessengerService` | `messenger/v1` |
| `$sdk->settings()` | `SettingsService` | `settings/v1` |
| `$sdk->auth()` | `AuthService` | `auth/v1` |

## Examples

### CRM

```php
// Entities
$sdk->crm()->getEntityTypes();
$sdk->crm()->getEntities('deals', ['page' => 1, 'list' => 50]);
$sdk->crm()->getContacts(['q' => 'ada@example.com']);

$sdk->crm()->createDeal(['title' => 'New deal', 'amount' => 1000]);
$sdk->crm()->patchEntity('deals', 42, ['amount' => 1500]);
$sdk->crm()->massEditEntities('deals', ['all' => true, 'settings' => [/* ... */]]);

// Fields
$sdk->crm()->getFields('deals');
$sdk->crm()->createField('deals', ['name' => 'Priority', 'type' => 'list']);
$sdk->crm()->deleteField('deals', 'priority');

// Funnels & kanban stages
$sdk->crm()->getFunnels('deals');
$sdk->crm()->getFunnelStagesByFunnelId('deals', 3);
$sdk->crm()->moveFunnelStage('deals', 42, 'stage-id', ['reason' => 'won']);

// Products, calls, CRM tasks
$sdk->crm()->createProduct(['name' => 'Widget', 'price' => 9.99]);
$sdk->crm()->createCall(['direction' => 'inbound', 'phone' => '+380...']);
$sdk->crm()->createTask(['title' => 'Follow up']);
```

### Tasks

```php
$sdk->tasks()->getTasks(['page' => 1]);
$sdk->tasks()->createTask(['title' => 'Ship SDK', 'responsibleId' => 7]);
$sdk->tasks()->patchTask(15, ['title' => 'Ship SDK v1']);
$sdk->tasks()->markTaskReady(15);
$sdk->tasks()->getKanbanStages(['groupId' => 3]);
```

### Users & departments

```php
$sdk->users()->getAllUsers();
$sdk->users()->getUsers(['page' => 2, 'list' => 20]);
$sdk->users()->patchUser(7, ['position' => 'CTO']);

$sdk->departments()->getDepartments();
$sdk->departments()->addUsers(4, [7, 8, 9]);
```

### Files (multipart upload)

```php
$sdk->files()->uploadFiles(
    [['name' => 'contract.pdf', 'data' => file_get_contents('/path/contract.pdf')]],
    entityType: 'deals',
    entityId: '42',
);

$sdk->files()->getFileById(101);
$sdk->files()->deleteFilesByEntity('deals', 42);
```

### Messenger

```php
$sdk->messenger()->getExternalLines();
$sdk->messenger()->createMessage(['chatId' => 1, 'text' => 'Hello']);
$sdk->messenger()->goToMessage('message-id');
```

### Auth

```php
$tokens = $sdk->auth()->applicationSignIn($clientId, $clientSecret); // Tokens DTO
$sdk->auth()->refreshToken();
```

## Error handling

The connector uses Saloon's `AlwaysThrowOnErrors`, so non-2xx responses throw a
`Saloon\Exceptions\Request\RequestException`. Message creation additionally throws
`Uspacy\SDK\Exceptions\MessageDuplicationException` on duplicate-key errors.

```php
use Saloon\Exceptions\Request\RequestException;

try {
    $sdk->crm()->createDeal(['title' => 'New']);
} catch (RequestException $e) {
    $status = $e->getResponse()->status();
    $body = $e->getResponse()->json();
}
```

## Development

```bash
composer install
composer check-cs   # code style (ECS, PSR-12 + clean-code)
composer fix-cs     # auto-fix style
```
