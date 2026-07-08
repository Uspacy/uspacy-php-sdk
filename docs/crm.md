# CRM entities

CRM entities (deals, leads, contacts, companies, …) are almost entirely made of
**portal-specific custom fields** — the API guarantees only `id`. So `EntityDTO`
types `id` and exposes everything else (including custom fields) via `get()` /
`has()` and the full `raw` payload.

Two ways in:

- `$sdk->crmDeals()` / `crmLeads()` / `crmContacts()` / `crmCompanies()` —
  entity-scoped `CrmEntityService`.
- `$sdk->crm()` — the generic `CrmService` (pass the entity type as a string).

```php
use Uspacy\SDK\Http\Client\UspacySDK;

$sdk = new UspacySDK('https://acme.uspacy.ua', $accessToken);
```

## Entities

```php
// List -> Collection<EntityDTO>
$page = $sdk->crmDeals()->getEntities(['page' => 1, 'list' => 20]);
foreach ($page->data as $deal) {
    $deal->id;                  // typed
    $deal->get('title');        // any standard field
    $deal->get('customfield_1'); // custom field (or null)
}
$page->meta->total;

// generic service equivalent
$sdk->crm()->getDeals(['page' => 1]);      // Collection<EntityDTO>
$sdk->crm()->getEntities('deals', []);     // Collection<EntityDTO>

// Single writes -> EntityDTO
$deal = $sdk->crmDeals()->createEntity(['title' => 'New deal', 'customfield_1' => 'x']);
$deal->id;
$deal->get('customfield_1');

$sdk->crmDeals()->updateEntity(42, ['title' => 'Renamed']);         // EntityDTO
$sdk->crmDeals()->moveFromStageToStage(42, 9, reasonId: 3);          // EntityDTO
$sdk->crmDeals()->getByStage(9);                                     // Collection<EntityDTO>

// Deletes / mass ops return the raw Response
$sdk->crmDeals()->deleteEntity(42);
$sdk->crmDeals()->massDeletion(entityIds: [1, 2, 3]);
$sdk->crmDeals()->massEditing(payload: ['customfield_1' => 'y'], entityIds: [1, 2]);
```

### Custom fields

Every `EntityDTO` keeps the complete payload, so custom fields are never lost:

```php
$deal = $sdk->crmDeals()->getEntities()->data[0];

$deal->has('customfield_2');            // true / false
$deal->get('customfield_2');            // value or null
$deal->get('customfield_2', 'default'); // value or default
$deal->raw;                             // the complete, untouched API payload
```

## Fields

```php
// List -> FieldDTO[]
$fields = $sdk->crmDeals()->getFields();
foreach ($fields as $field) {
    $field->code;        // 'title'
    $field->type;        // 'string'
    $field->required;    // bool
    $field->values;      // list values for dropdowns
    $field->get('sort'); // any other IField property
}

// generic service
$sdk->crm()->getFields('deals');            // FieldDTO[]
$sdk->crm()->getField('deals', 'amount');   // FieldDTO

// create / update -> FieldDTO
$sdk->crmDeals()->createField(['name' => 'Priority', 'code' => 'priority', 'type' => 'list']);
$sdk->crmDeals()->updateField('priority', ['name' => 'Priority level']);

// delete + list-value ops return raw Response
$sdk->crmDeals()->deleteField('priority');
$sdk->crmDeals()->updateListValues('priority', [['value' => 'High']]);
$sdk->crmDeals()->deleteListValue('priority', 'value-id');
```

## Return-type reference (CrmEntityService + generic CrmService)

| Method | Returns |
| --- | --- |
| `getEntities`, `getByStage`, `getContacts/Companies/Leads/Deals` | `Collection<EntityDTO>` |
| `createEntity`, `updateEntity`, `patchEntity`, `moveFromStageToStage`, `create{Contact,Company,Lead,Deal}` | `EntityDTO` |
| `getFields` | `FieldDTO[]` |
| `getField`, `createField`, `updateField` | `FieldDTO` |
| `deleteEntity`, `massDeletion`, `massEditing`, `massEditEntities`, `deleteField`, `updateListValues`, `deleteListValue` | `Saloon\Http\Response` |

> Funnels, stages, products, catalog, requisites and document templates are typed
> with the same ruleset in follow-up work.
