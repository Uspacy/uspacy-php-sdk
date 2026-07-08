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

## Funnels, stages & reasons

`$sdk->crmDealsFunnels()` / `crmLeadsFunnels()` (`CrmFunnelsService`) and
`$sdk->crmDealsStages()` / `crmLeadsStages()` (`CrmStagesService`) return typed
funnel/stage/reason DTOs. Nested collections are hydrated too, so a funnel's
stages and a stage's reasons are typed all the way down.

```php
// Funnels -> FunnelDTO[] (with nested stages)
$funnels = $sdk->crmDealsFunnels()->getFunnels();
$funnels[0]->title;
$funnels[0]->funnelCode;
$funnels[0]->isDefault;               // bool (API field: default)
$funnels[0]->stages[0]->stageCode;    // nested StageDTO

$sdk->crmDealsFunnels()->createFunnel(['title' => 'Sales']);   // FunnelDTO
$sdk->crmDealsFunnels()->updateFunnel(3, ['title' => 'B2B']);  // FunnelDTO
$sdk->crmDealsFunnels()->deleteFunnel(3);                      // raw Response

// Stages -> StageDTO[]
$stages = $sdk->crmDealsStages()->getStages();
$stages[0]->stageCode;
$stages[0]->color;
$stages[0]->reasons;                  // nested ReasonDTO[]
$sdk->crmDealsFunnels()->getStagesByFunnel(3);                 // StageDTO[]

$sdk->crmDealsStages()->createStage(['title' => 'New']);       // StageDTO
$sdk->crmDealsStages()->updateStage(10, ['color' => '#fff']);  // StageDTO
$sdk->crmDealsStages()->deleteStage(10);                       // raw Response

// Reasons -> ReasonsDTO (grouped success / fail)
$reasons = $sdk->crmDealsStages()->getReasons(42);
$reasons->success;   // ReasonDTO[]
$reasons->fail;      // ReasonDTO[]

$sdk->crmDealsStages()->createReason(42, ['title' => 'Won']);  // ReasonDTO
$sdk->crmDealsFunnels()->createStageReason(3, ['title' => 'Lost']); // ReasonDTO
$sdk->crmDealsStages()->deleteReason(9);                       // raw Response
```

### Return-type reference (funnels & stages)

| Method | Returns |
| --- | --- |
| `getFunnels` | `FunnelDTO[]` |
| `createFunnel`, `updateFunnel` | `FunnelDTO` |
| `getStages`, `getStagesByFunnel` | `StageDTO[]` |
| `createStage`, `updateStage` | `StageDTO` |
| `getReasons` | `ReasonsDTO` (grouped `success` / `fail`) |
| `createReason`, `updateReason`, `createStageReason`, `updateStageReason` | `ReasonDTO` |
| `deleteFunnel`, `deleteStage`, `deleteReason`, `deleteStageReason` | `Saloon\Http\Response` |

> Products, catalog, requisites and document templates are typed with the same
> ruleset in follow-up work.
