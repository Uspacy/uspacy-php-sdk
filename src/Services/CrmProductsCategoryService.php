<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

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
     */
    public function getProductCategories(): Response
    {
        return $this->http->get(self::NAMESPACE);
    }

    /**
     * Create a product category.
     */
    public function createProductCategory(array $data): Response
    {
        return $this->http->post(self::NAMESPACE, $data);
    }

    /**
     * Update a product category.
     *
     * @param  int|string  $id
     */
    public function updateProductCategory($id, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/{$id}", $data);
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
