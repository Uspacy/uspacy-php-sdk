<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * CRM document templates service.
 *
 * Mirrors the JS SDK's CrmDocumentTemplatesService (`/crm/v1/documents/templates`).
 */
class CrmDocumentTemplatesService extends Service
{
    private const NAMESPACE = '/crm/v1/documents/templates';

    /**
     * Get document templates.
     *
     * @param  array  $params  filter params
     */
    public function getDocumentTemplates(array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE, $params);
    }

    /**
     * Get the fields available for document templates.
     *
     * @param  array  $params  filter params
     */
    public function getDocumentTemplatesFields(array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . '/fields', $params);
    }

    /**
     * Create a document template.
     */
    public function createTemplate(array $data): Response
    {
        return $this->http->post(self::NAMESPACE, $data);
    }

    /**
     * Update a document template.
     *
     * @param  int|string  $id
     */
    public function updateTemplate($id, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/{$id}", $data);
    }

    /**
     * Delete a document template.
     *
     * @param  int|string  $id
     */
    public function deleteTemplate($id): Response
    {
        return $this->http->delete(self::NAMESPACE . "/{$id}");
    }

    /**
     * Delete several document templates at once.
     *
     * @param  array  $ids  template ids
     */
    public function deleteArrayTemplates(array $ids): Response
    {
        return $this->http->delete(self::NAMESPACE, [], ['ids' => $ids]);
    }
}
