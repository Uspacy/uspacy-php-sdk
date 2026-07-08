# Messenger service

`$sdk->messenger()` covers the `messenger/v1` module. A **subset** of methods with
clean, well-defined shapes return typed DTOs (namespace `Uspacy\SDK\DTOs\Messenger`);
the rest — messages, external lines, widgets, chat relations — return the raw
`Saloon\Http\Response` (their payloads are heterogeneous / bespoke). Every output
DTO keeps the full `raw` payload.

```php
use Uspacy\SDK\Http\Client\UspacySDK;

$sdk = new UspacySDK('https://acme.uspacy.ua', $accessToken);
```

## Chats

```php
// -> ChatDTO[]
$chats = $sdk->messenger()->getChats(['status' => 'active']);
$chats[0]->id;        // string
$chats[0]->name;
$chats[0]->type;
$chats[0]->members;
$chats[0]->get('lastMessage'); // full payload available via get()/raw
```

## Quick answers (quick replies)

```php
$sdk->messenger()->getQuickAnswers(['status' => 'active']);   // QuickAnswerDTO[]
$sdk->messenger()->getQuickAnswerById('q1');                  // QuickAnswerDTO

$sdk->messenger()->createQuickAnswer(['name' => 'Greeting', 'message' => 'Hi!']);  // QuickAnswerDTO
$sdk->messenger()->updateQuickAnswer('q1', ['message' => 'Hello!']);               // QuickAnswerDTO
$sdk->messenger()->updateQuickAnswerStatus('q1', 'inactive');                      // QuickAnswerDTO
$sdk->messenger()->deleteQuickAnswer('q1');                                        // raw Response
```

## User settings

```php
$settings = $sdk->messenger()->getSettings();                 // UserSettingsDTO
$settings->isInternalMsgSoundEnabled;
$sdk->messenger()->updateSettings(['isInternalMsgSoundEnabled' => false]); // UserSettingsDTO
```

## Return-type reference

| Method | Returns |
| --- | --- |
| `getChats` | `ChatDTO[]` |
| `getQuickAnswers` | `QuickAnswerDTO[]` |
| `getQuickAnswerById`, `createQuickAnswer`, `updateQuickAnswer`, `updateQuickAnswerStatus` | `QuickAnswerDTO` |
| `getSettings`, `updateSettings` | `UserSettingsDTO` |
| everything else (messages, external lines, widgets, relations, pinned, read-all, …) | `Saloon\Http\Response` |
