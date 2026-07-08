<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class CrmProductsTest extends TestCase
{
    public function test_products_catalog_crud(): void
    {
        $this->sdk->crmProducts()->getProducts(['page' => 1]);
        $this->assertRequestSent('GET', '/crm/v1/static/products', null, ['page' => 1]);

        $this->sdk->crmProducts()->createProduct(['name' => 'Widget']);
        $this->assertRequestSent('POST', '/crm/v1/static/products', ['name' => 'Widget']);

        $this->sdk->crmProducts()->updateProduct(3, ['name' => 'W2']);
        $this->assertRequestSent('PATCH', '/crm/v1/static/products/3', ['name' => 'W2']);

        $this->sdk->crmProducts()->deleteProduct(3);
        $this->assertRequestSent('DELETE', '/crm/v1/static/products/3');
    }

    public function test_product_fields_use_dynamic_namespace(): void
    {
        $this->sdk->crmProducts()->getFields();
        $this->assertRequestSent('GET', '/crm/v1/entities/products/fields');

        $this->sdk->crmProducts()->createField(['name' => 'F']);
        $this->assertRequestSent('POST', '/crm/v1/entities/products/fields', ['name' => 'F']);

        $this->sdk->crmProducts()->deleteListValue('code1', 'val1');
        $this->assertRequestSent('DELETE', '/crm/v1/entities/products/lists/code1/val1');
    }

    public function test_categories_units_taxes_price_types(): void
    {
        $this->sdk->crmProductsUnits()->getProductUnits();
        $this->assertRequestSent('GET', '/crm/v1/static/measurement-units');

        $this->sdk->crmProductsTaxes()->createProductTax(['name' => 'VAT']);
        $this->assertRequestSent('POST', '/crm/v1/static/taxes', ['name' => 'VAT']);

        $this->sdk->crmProductsPriceTypes()->getProductPriceTypes();
        $this->assertRequestSent('GET', '/crm/v1/static/product-price-types');

        $this->sdk->crmProductsCategories()->getProductCategories();
        $this->assertRequestSent('GET', '/crm/v1/static/product-categories');
    }

    public function test_category_delete_builds_query(): void
    {
        $this->sdk->crmProductsCategories()->deleteProductCategory(5, 1, true);

        $this->assertRequestSent(
            'DELETE',
            '/crm/v1/static/product-categories/5',
            null,
            ['category_for_products' => 1, 'child_categories' => 'delete'],
        );
    }

    public function test_products_for_entity(): void
    {
        $this->sdk->crmProductsForEntity()->getInfoProductsForEntity('deals', 42);
        $this->assertRequestSent('GET', '/crm/v1/static/entity-product-lists', null, ['entity_type' => 'deals', 'entity_id' => 42]);

        $this->sdk->crmProductsForEntity()->createProductsForEntity([['id' => 1]]);
        $this->assertRequestSent('POST', '/crm/v1/static/list-products/bulk', ['list_products' => [['id' => 1]]]);

        $this->sdk->crmProductsForEntity()->deleteProductsForEntity([1, 2]);
        $this->assertRequestSent('DELETE', '/crm/v1/static/list-products/bulk', null, ['list_products' => [1, 2]]);
    }
}
