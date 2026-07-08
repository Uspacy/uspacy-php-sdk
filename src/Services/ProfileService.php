<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Profile service (the current authenticated user).
 *
 * Mirrors the JS SDK's ProfileService: the "me" endpoints under
 * `/company/v1/users/me`, profile custom fields under
 * `/company/v1/custom_fields/users`, and the user's own requisites under
 * `/crm/v1/requisites`.
 */
class ProfileService extends Service
{
    private const NAMESPACE = '/company/v1/users/me';

    private const FIELDS = '/company/v1/custom_fields/users';

    private const REQUISITES = '/crm/v1/requisites';

    /**
     * Get the current user's profile.
     */
    public function getProfile(): Response
    {
        return $this->http->get(self::NAMESPACE . '/');
    }

    /**
     * Get the current user's online status.
     */
    public function getProfileOnlineStatus(): Response
    {
        return $this->http->get(self::NAMESPACE . '/online');
    }

    /**
     * Get the current user's two-factor authentication status.
     */
    public function get2FaStatus(): Response
    {
        return $this->http->get(self::NAMESPACE . '/twofa_status/');
    }

    /**
     * Enable two-factor authentication for the current user.
     */
    public function enable2Fa(): Response
    {
        return $this->http->patch(self::NAMESPACE . '/twofa_enable/');
    }

    /**
     * Disable two-factor authentication for the current user.
     */
    public function disable2Fa(): Response
    {
        return $this->http->patch(self::NAMESPACE . '/twofa_disable/');
    }

    /**
     * Get the current user's portal settings.
     */
    public function getPortalSettings(): Response
    {
        return $this->http->get(self::NAMESPACE . '/settings/');
    }

    /**
     * Update the current user's portal settings.
     */
    public function updatePortalSettings(array $settings): Response
    {
        return $this->http->patch(self::NAMESPACE . '/settings/', $settings);
    }

    /**
     * Get the current user's requisites.
     */
    public function getRequisites(): Response
    {
        return $this->http->get(self::REQUISITES . '/');
    }

    /**
     * Update a requisite.
     *
     * @param  int|string  $id
     */
    public function updateRequisite($id, array $body): Response
    {
        return $this->http->patch(self::REQUISITES . "/{$id}", $body);
    }

    /**
     * Remove a requisite.
     *
     * @param  int|string  $id
     */
    public function removeRequisite($id): Response
    {
        return $this->http->delete(self::REQUISITES . "/{$id}");
    }

    /**
     * Get requisite templates.
     */
    public function getTemplates(?int $page = null, ?int $list = null): Response
    {
        return $this->http->get(self::REQUISITES . '/templates', ['page' => $page, 'list' => $list]);
    }

    /**
     * Get the basic (built-in) requisite templates.
     */
    public function getBasicTemplates(): Response
    {
        return $this->http->get(self::REQUISITES . '/templates/basic-templates');
    }

    /**
     * Create a requisite template.
     */
    public function createTemplate(array $body): Response
    {
        return $this->http->post(self::REQUISITES . '/templates', $body);
    }

    /**
     * Update a requisite template.
     *
     * @param  int|string  $id
     */
    public function updateTemplate($id, array $body): Response
    {
        return $this->http->patch(self::REQUISITES . "/templates/{$id}", $body);
    }

    /**
     * Remove a requisite template.
     *
     * @param  int|string  $id
     */
    public function removeTemplate($id): Response
    {
        return $this->http->delete(self::REQUISITES . "/templates/{$id}");
    }

    /**
     * Get bank requisites for a requisite.
     *
     * @param  int|string  $id
     */
    public function getBankRequisitesById($id): Response
    {
        return $this->http->get(self::REQUISITES . "/{$id}/bank_requisites/");
    }

    /**
     * Get the profile custom fields.
     */
    public function getProfileFields(): Response
    {
        return $this->http->get(self::FIELDS . '/fields');
    }

    /**
     * Create a profile custom field.
     */
    public function createProfileField(array $data): Response
    {
        return $this->http->post(self::FIELDS . '/fields', $data);
    }

    /**
     * Update a profile custom field.
     */
    public function updateProfileField(string $fieldCode, array $data): Response
    {
        return $this->http->patch(self::FIELDS . "/fields/{$fieldCode}", $data);
    }

    /**
     * Delete a profile custom field.
     */
    public function deleteProfileField(string $fieldCode): Response
    {
        return $this->http->delete(self::FIELDS . "/fields/{$fieldCode}");
    }

    /**
     * Add/replace list values for a profile custom field.
     */
    public function updateProfileListValues(string $fieldCode, array $values): Response
    {
        return $this->http->post(self::FIELDS . "/lists/{$fieldCode}", $values);
    }

    /**
     * Delete a single list value from a profile custom field.
     */
    public function deleteProfileListValues(string $fieldCode, string $value): Response
    {
        return $this->http->delete(self::FIELDS . "/lists/{$fieldCode}/{$value}");
    }
}
