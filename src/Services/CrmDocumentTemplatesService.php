<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\DTOs\Collection;
use Uspacy\SDK\DTOs\Crm\DocumentTemplateDTO;
use Uspacy\SDK\DTOs\Crm\DocumentTemplateFieldDTO;

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
     * @return Collection<DocumentTemplateDTO>
     */
    public function getDocumentTemplates(array $params = []): Collection
    {
        return Collection::fromArray(
            $this->http->get(self::NAMESPACE, $params)->json() ?? [],
            [DocumentTemplateDTO::class, 'fromArray'],
        );
    }

    /**
     * Get the fields available for document templates.
     *
     * @param  array  $params  filter params
     * @return array<int, DocumentTemplateFieldDTO>
     */
    public function getDocumentTemplatesFields(array $params = []): array
    {
        $data = $this->http->get(self::NAMESPACE . '/fields', $params)->json() ?? [];

        return array_map([DocumentTemplateFieldDTO::class, 'fromArray'], $data['data'] ?? []);
    }

    /**
     * Create a document template.
     */
    public function createTemplate(array $data): DocumentTemplateDTO
    {
        return DocumentTemplateDTO::fromArray($this->http->post(self::NAMESPACE, $data)->json() ?? []);
    }

    /**
     * Update a document template.
     *
     * @param  int|string  $id
     */
    public function updateTemplate($id, array $data): DocumentTemplateDTO
    {
        return DocumentTemplateDTO::fromArray($this->http->patch(self::NAMESPACE . "/{$id}", $data)->json() ?? []);
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
