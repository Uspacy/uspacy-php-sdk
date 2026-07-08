# Users service

`$sdk->users()` covers user management under `company/v1`. Read/write methods
return **typed DTOs** (namespace `Uspacy\SDK\DTOs\Users`); every output DTO also
keeps the full raw payload, so **custom fields are never lost**.

```php
use Uspacy\SDK\Http\Client\UspacySDK;

$sdk = new UspacySDK('https://acme.uspacy.ua', $accessToken);
```

## Reading users

```php
// Single user -> UserDTO
$user = $sdk->users()->getUserById(7);
$user->id;         // 7
$user->firstName;  // 'Ada'
$user->active;     // true
$user->roles;      // ['admin', ...]

// Paginated list -> Collection<UserDTO>
$page = $sdk->users()->getUsers(['page' => 1, 'list' => 20]);
foreach ($page->data as $user) {
    echo $user->firstName;
}
$page->meta->total; // total count
$page->meta->page;  // current page

// All users (no pagination) -> UserDTO[]
$all = $sdk->users()->getAllUsers();

// By ids -> Collection<UserDTO>
$some = $sdk->users()->getUsersByIds([1, 2, 3]);
```

### Typed filters (optional)

`getUsers()` and `search()` accept either a plain array or a typed filter DTO:

```php
use Uspacy\SDK\DTOs\Users\UserFilterDTO;
use Uspacy\SDK\DTOs\Users\SearchUsersDTO;

$sdk->users()->getUsers(new UserFilterDTO(page: 2, list: 50, show: 'all'));
$sdk->users()->search(new SearchUsersDTO(search: 'ada@example.com'));

// Anything not modeled goes through `extra`:
$sdk->users()->getUsers(new UserFilterDTO(page: 1, extra: ['department' => [4]]));
```

## Custom fields

Portal-specific custom fields are returned by the API but not modeled as typed
properties. They are fully preserved and readable with `get()` / `has()`:

```php
$user = $sdk->users()->getUserById(7);

$user->has('customfield_1');            // true / false
$user->get('customfield_1');            // the value, or null if absent
$user->get('customfield_2', 'default'); // value or the provided default

// The complete, untouched API payload is also available:
$user->raw['customfield_1'];
```

This applies to every output DTO that carries a `raw` payload (`UserDTO`,
`PortalSettingsDTO`, `TwoFaStatusDTO`, `Meta`, ...).

## Writing users

Update methods accept either a plain array or a typed input DTO, and return the
updated `UserDTO`:

```php
use Uspacy\SDK\DTOs\Users\UpdateUserDTO;
use Uspacy\SDK\DTOs\Users\UpdateRolesDTO;

// Plain array
$sdk->users()->updateUser(7, ['position' => 'CTO']);

// Typed DTO — custom fields go through `extra`
$sdk->users()->updateUser(7, new UpdateUserDTO(
    position: 'CTO',
    extra: ['customfield_1' => 'value'],
));

$sdk->users()->patchUser(7, ['firstName' => 'Ada']);

// Roles: a typed DTO or a plain list of role ids
$sdk->users()->updateRoles(7, new UpdateRolesDTO(['admin', 'manager']));
$sdk->users()->updateRoles(7, ['viewer']);

$sdk->users()->updatePosition(7, 'CTO');
$sdk->users()->activateUser(7);
$sdk->users()->deactivateUser(7);
```

## Avatars, 2FA, settings, status

```php
// Avatar (multipart) -> UserDTO
$sdk->users()->uploadAvatar(file_get_contents('/path/avatar.png'), userId: 7, filename: 'avatar.png');

// Two-factor status -> TwoFaStatusDTO
$sdk->users()->get2FaStatus(7)->enabled; // bool
$sdk->users()->disable2Fa(7);            // raw Response

// Portal settings -> PortalSettingsDTO
$settings = $sdk->users()->getPortalSettings(7);
$settings->lang;                 // 'uk'
$settings->availableCurrencies;  // ['UAH', 'USD']
$sdk->users()->updatePortalSettings(7, ['lang' => 'en']);

// Online statuses -> UserOnlineStatusesDTO (map keyed by user id)
$statuses = $sdk->users()->getUsersOnlineStatuses();
$statuses->statuses['7']->isOnline;   // bool
$statuses->statuses['7']->lastSeenAt; // int

// Registration link -> raw Response
$sdk->users()->getUserRegisterLink(7)->json()['link'];
```

## Invitations

```php
use Uspacy\SDK\DTOs\Users\ImportRegisteredUserDTO;

$sdk->users()->importRegisteredUser(new ImportRegisteredUserDTO(
    email: 'ada@example.com',
    roles: ['manager'],
));
```

## Return-type reference

| Method | Returns |
| --- | --- |
| `getUsers`, `search`, `getUsersByIds` | `Collection<UserDTO>` |
| `getAllUsers` | `UserDTO[]` |
| `getUserById`, `patchUser`, `updateUser`, `activateUser`, `deactivateUser`, `updateRoles`, `updatePosition`, `uploadAvatar`, `importRegisteredUser` | `UserDTO` |
| `get2FaStatus` | `TwoFaStatusDTO` |
| `getPortalSettings`, `updatePortalSettings` | `PortalSettingsDTO` |
| `getUsersOnlineStatuses` | `UserOnlineStatusesDTO` |
| `getUserRegisterLink`, `disable2Fa` | `Saloon\Http\Response` |
