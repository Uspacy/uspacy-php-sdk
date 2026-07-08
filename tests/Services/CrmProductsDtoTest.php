<?php

namespace Uspacy\SDK\Tests\Services;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Uspacy\SDK\DTOs\Collection;
use Uspacy\SDK\DTOs\Crm\CategoryDTO;
use Uspacy\SDK\DTOs\Crm\PriceTypeDTO;
use Uspacy\SDK\DTOs\Crm\ProductDTO;
use Uspacy\SDK\DTOs\Crm\ProductForEntityDTO;
use Uspacy\SDK\DTOs\Crm\ProductInfoForEntityDTO;
use Uspacy\SDK\DTOs\Crm\TaxDTO;
use Uspacy\SDK\DTOs\Crm\UnitDTO;
use Uspacy\SDK\Http\Client\Requests\GetRequest;
use Uspacy\SDK\Tests\TestCase;

class CrmProductsDtoTest extends TestCase
{
    public function test_get_products_hydrates_collection(): void
    {
        $this->mockGet([
            'data' => [
                ['id' => 1, 'title' => 'Widget', 'type' => 'goods', 'is_active' => true, 'customfield_1' => 'x'],
            ],
            'meta' => ['total' => 1],
        ]);

        $result = $this->sdk->crmProducts()->getProducts();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertInstanceOf(ProductDTO::class, $result->data[0]);
        $this->assertSame('Widget', $result->data[0]->title);
        $this->assertTrue($result->data[0]->isActive);
        $this->assertSame('x', $result->data[0]->get('customfield_1'));
        $this->assertSame(1, $result->meta->total);
    }

    public function test_catalog_lists_hydrate_from_data_envelope(): void
    {
        $this->mockGet(['data' => [['id' => 1, 'name' => 'kg', 'abbr' => 'kg', 'is_default' => 1]]]);
        $units = $this->sdk->crmProductsUnits()->getProductUnits();
        $this->assertInstanceOf(UnitDTO::class, $units[0]);
        $this->assertSame('kg', $units[0]->abbr);

        $this->mockGet(['data' => [['id' => 2, 'name' => 'VAT', 'rate' => 20, 'is_active' => 1]]]);
        $taxes = $this->sdk->crmProductsTaxes()->getProductTaxes();
        $this->assertInstanceOf(TaxDTO::class, $taxes[0]);
        $this->assertSame(20, $taxes[0]->rate);

        $this->mockGet(['data' => [['id' => 3, 'title' => 'Retail', 'default' => true, 'active' => true]]]);
        $priceTypes = $this->sdk->crmProductsPriceTypes()->getProductPriceTypes();
        $this->assertInstanceOf(PriceTypeDTO::class, $priceTypes[0]);
        $this->assertTrue($priceTypes[0]->isDefault);
    }

    public function test_categories_hydrate_nested_children(): void
    {
        $this->mockGet([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Root',
                    'parent_id' => 0,
                    'child_categories' => [
                        ['id' => 2, 'name' => 'Child', 'parent_id' => 1],
                    ],
                ],
            ],
        ]);

        $categories = $this->sdk->crmProductsCategories()->getProductCategories();

        $this->assertInstanceOf(CategoryDTO::class, $categories[0]);
        $this->assertSame('Root', $categories[0]->name);
        $this->assertInstanceOf(CategoryDTO::class, $categories[0]->childCategories[0]);
        $this->assertSame('Child', $categories[0]->childCategories[0]->name);
    }

    public function test_products_for_entity_info_with_nested_line_products(): void
    {
        $this->mockGet([
            'id' => 5,
            'entity_type' => 'deals',
            'entity_id' => 42,
            'amount_total' => 300,
            'list_products' => [
                ['id' => 1, 'title' => 'Line A', 'price' => 100, 'quantity' => 2, 'currency' => 'UAH'],
            ],
        ]);

        $info = $this->sdk->crmProductsForEntity()->getInfoProductsForEntity('deals', 42);

        $this->assertInstanceOf(ProductInfoForEntityDTO::class, $info);
        $this->assertSame('deals', $info->entityType);
        $this->assertSame(300, $info->amountTotal);
        $this->assertInstanceOf(ProductForEntityDTO::class, $info->listProducts[0]);
        $this->assertSame('Line A', $info->listProducts[0]->title);
        $this->assertSame(100, $info->listProducts[0]->price);
    }

    public function test_empty_body_does_not_throw(): void
    {
        $this->sdk->withMockClient(new MockClient([
            GetRequest::class => MockResponse::make('', 204),
        ]));

        $this->assertInstanceOf(Collection::class, $this->sdk->crmProducts()->getProducts());
        $this->assertSame([], $this->sdk->crmProductsUnits()->getProductUnits());
        $this->assertSame([], $this->sdk->crmProductsForEntity()->getProductsForEntity());
    }

    private function mockGet(array $payload): void
    {
        $this->sdk->withMockClient(new MockClient([
            GetRequest::class => MockResponse::make($payload, 200),
        ]));
    }
}
