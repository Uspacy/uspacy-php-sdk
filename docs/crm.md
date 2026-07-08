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

## Products & catalog

```php
// Products -> Collection<ProductDTO>
$page = $sdk->crmProducts()->getProducts(['page' => 1]);
$page->data[0]->title;
$page->data[0]->isActive;
$page->data[0]->get('customfield_1');   // products carry custom fields too

$sdk->crmProducts()->createProduct(['title' => 'Widget']);   // ProductDTO
$sdk->crmProducts()->updateProduct(3, ['title' => 'Widget2']); // ProductDTO
$sdk->crmProducts()->deleteProduct(3);                        // raw Response

// Product fields -> FieldDTO[] (dynamic namespace)
$sdk->crmProducts()->getFields();
$sdk->crmProducts()->createField(['name' => 'Colour', 'code' => 'colour']); // FieldDTO

// Catalog references -> DTO[]
$sdk->crmProductsCategories()->getProductCategories(); // CategoryDTO[] (nested childCategories)
$sdk->crmProductsUnits()->getProductUnits();           // UnitDTO[]
$sdk->crmProductsTaxes()->getProductTaxes();           // TaxDTO[]
$sdk->crmProductsPriceTypes()->getProductPriceTypes(); // PriceTypeDTO[]

// each supports create/update -> DTO, delete -> raw Response
$sdk->crmProductsTaxes()->createProductTax(['name' => 'VAT', 'rate' => 20]); // TaxDTO
```

### Line products (products attached to an entity)

```php
// Aggregated info -> ProductInfoForEntityDTO (nested listProducts)
$info = $sdk->crmProductsForEntity()->getInfoProductsForEntity('deals', 42);
$info->amountTotal;
$info->listProducts[0]->title;   // nested ProductForEntityDTO

// Line products
$sdk->crmProductsForEntity()->getProductsForEntity();      // ProductForEntityDTO[]
$sdk->crmProductsForEntity()->getProductForEntity(7);      // ProductForEntityDTO
$sdk->crmProductsForEntity()->updateProductForEntity(7, ['quantity' => 3]); // ProductForEntityDTO
$sdk->crmProductsForEntity()->createProductsForEntity([['product_id' => 1, 'quantity' => 2]]); // ProductForEntityDTO[]
$sdk->crmProductsForEntity()->deleteProductsForEntity([1, 2]); // raw Response
```

### Return-type reference (products & catalog)

| Method | Returns |
| --- | --- |
| `crmProducts()->getProducts` | `Collection<ProductDTO>` |
| `crmProducts()->createProduct` / `updateProduct` | `ProductDTO` |
| `crmProducts()->getFields` | `FieldDTO[]` |
| `crmProducts()->createField` / `updateField` | `FieldDTO` |
| `crmProductsCategories/Units/Taxes/PriceTypes()->get…` | `CategoryDTO[]` / `UnitDTO[]` / `TaxDTO[]` / `PriceTypeDTO[]` |
| … `->create…` / `->update…` | the matching single DTO |
| `crmProductsForEntity()->getInfoProductsForEntity` / `updateInfoProductForEntity` / `createProductForEntity` | `ProductInfoForEntityDTO` |
| `crmProductsForEntity()->getProductForEntity` / `updateProductForEntity` | `ProductForEntityDTO` |
| `crmProductsForEntity()->getProductsForEntity` / `create…` / `update…` (bulk) | `ProductForEntityDTO[]` |
| all delete / mass / list-value methods | `Saloon\Http\Response` |

## Requisites

`$sdk->crmRequisites()` covers card requisites, their nested bank requisites, and
requisite templates under `/crm/v1/requisites`.

```php
// Lists -> Collection
$sdk->crmRequisites()->getCardRequisites(['entity_id' => 5]); // Collection<RequisiteDTO>
$sdk->crmRequisites()->getTemplates(['page' => 1]);           // Collection<RequisiteTemplateDTO>

$card = $sdk->crmRequisites()->getCardRequisites()->data[0];
$card->name;
$card->isBasic;
$card->templateId;
$card->get('customfield_1');   // requisites carry custom fields too

// Card requisites -> RequisiteDTO
$sdk->crmRequisites()->createCardRequisites(['name' => 'Acme'], ['entity_id' => 5]);
$sdk->crmRequisites()->updateCardRequisites(9, ['name' => 'Acme LLC']);
$sdk->crmRequisites()->attachCardRequisites(['entity_id' => 5]);
$sdk->crmRequisites()->deleteCardRequisites(9);               // raw Response

// Bank requisites -> RequisiteDTO
$sdk->crmRequisites()->createBankRequisites(9, ['iban' => 'UA...']);
$sdk->crmRequisites()->updateBankRequisites(9, 3, ['iban' => 'UA2']);
$sdk->crmRequisites()->attachBankRequisites(9, ['entity_id' => 5]);
$sdk->crmRequisites()->deleteBankRequisites(9, 3);            // raw Response
```

## Document templates

`$sdk->crmDocumentTemplates()` — `/crm/v1/documents/templates`.

```php
$sdk->crmDocumentTemplates()->getDocumentTemplates(['page' => 1]); // Collection<DocumentTemplateDTO>
$sdk->crmDocumentTemplates()->getDocumentTemplatesFields();        // DocumentTemplateFieldDTO[]

$sdk->crmDocumentTemplates()->createTemplate(['name' => 'Invoice']);   // DocumentTemplateDTO
$sdk->crmDocumentTemplates()->updateTemplate(4, ['name' => 'Invoice2']); // DocumentTemplateDTO
$sdk->crmDocumentTemplates()->deleteTemplate(4);        // raw Response
$sdk->crmDocumentTemplates()->deleteArrayTemplates([1, 2, 3]); // raw Response
```

### Return-type reference (requisites & document templates)

| Method | Returns |
| --- | --- |
| `crmRequisites()->getCardRequisites` | `Collection<RequisiteDTO>` |
| `crmRequisites()->getTemplates` | `Collection<RequisiteTemplateDTO>` |
| `crmRequisites()->create/update/attach Card/Bank Requisites` | `RequisiteDTO` |
| `crmRequisites()->delete…` | `Saloon\Http\Response` |
| `crmDocumentTemplates()->getDocumentTemplates` | `Collection<DocumentTemplateDTO>` |
| `crmDocumentTemplates()->getDocumentTemplatesFields` | `DocumentTemplateFieldDTO[]` |
| `crmDocumentTemplates()->createTemplate` / `updateTemplate` | `DocumentTemplateDTO` |
| `crmDocumentTemplates()->deleteTemplate` / `deleteArrayTemplates` | `Saloon\Http\Response` |
