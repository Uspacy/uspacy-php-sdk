<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\DTOs\Crm\CategoryDTO;

/**
 * CRM product categories service.
 *
 * Mirrors the JS SDK's CrmProductsCategoryService (`/crm/v1/static/product-categories`).
 */
class CrmProductsCategoryService extends Service
{
    private const NAMESPACE = '/crm/v1/static/product-categories';

    /**
     * Get all product categories.
     *
     * @return array<int, CategoryDTO>
     */
    public function getProductCategories(): array
    {
        $data = $this->http->get(self::NAMESPACE)->json() ?? [];

        return array_map([CategoryDTO::class, 'fromArray'], $data['data'] ?? []);
    }

    /**
     * Create a product category.
     */
    public function createProductCategory(array $data): CategoryDTO
    {
        return CategoryDTO::fromArray($this->http->post(self::NAMESPACE, $data)->json() ?? []);
    }

    /**
     * Update a product category.
     *
     * @param  int|string  $id
     */
    public function updateProductCategory($id, array $data): CategoryDTO
    {
        return CategoryDTO::fromArray($this->http->patch(self::NAMESPACE . "/{$id}", $data)->json() ?? []);
    }

    /**
     * Delete a product category, moving its products to another category.
     *
     * @param  int|string  $id
     * @param  int|string  $categoryForProducts  category the orphaned products are moved to
     * @param  bool  $removeWithChild  also delete child categories
     */
    public function deleteProductCategory($id, $categoryForProducts, bool $removeWithChild = false): Response
    {
        $query = ['category_for_products' => $categoryForProducts];

        if ($removeWithChild) {
            $query['child_categories'] = 'delete';
        }

        return $this->http->delete(self::NAMESPACE . "/{$id}", [], $query);
    }
}
