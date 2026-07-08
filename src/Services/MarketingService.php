<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Marketing service.
 *
 * Mirrors the JS SDK's MarketingService: email templates (letters), email
 * newsletters (mailings), sending domains and senders under the `/marketing/v1`
 * module.
 */
class MarketingService extends Service
{
    private const TEMPLATES = '/marketing/v1/templates';

    private const NEWSLETTERS = '/marketing/v1/newsletters';

    /**
     * Get email templates.
     *
     * @param  array  $params  filter params
     */
    public function getEmailTemplates(array $params = []): Response
    {
        return $this->http->get(self::TEMPLATES . '/letters', $params);
    }

    /**
     * Get an email template by id.
     *
     * @param  int|string  $id
     */
    public function getEmailTemplate($id): Response
    {
        return $this->http->get(self::TEMPLATES . "/letters/{$id}");
    }

    /**
     * Create an email template.
     */
    public function createEmailTemplate(array $data): Response
    {
        return $this->http->post(self::TEMPLATES . '/letters', $data);
    }

    /**
     * Update an email template.
     *
     * @param  int|string  $id
     */
    public function updateEmailTemplate($id, array $data): Response
    {
        return $this->http->patch(self::TEMPLATES . "/letters/{$id}", $data);
    }

    /**
     * Delete an email template.
     *
     * @param  int|string  $id
     */
    public function deleteEmailTemplate($id): Response
    {
        return $this->http->delete(self::TEMPLATES . "/letters/{$id}");
    }

    /**
     * Mass edit email templates. The filter params are merged into the body.
     *
     * @param  array  $ids  template ids
     * @param  array  $payload  fields to apply
     * @param  bool  $all  edit every template matching $params
     * @param  array  $params  list filter params (merged into the body)
     */
    public function massEditingEmailTemplates(array $ids, array $payload, bool $all = false, array $params = []): Response
    {
        return $this->http->post(self::TEMPLATES . '/letters/mass_edit', array_merge([
            'all' => $all,
            'payload' => $payload,
            'id' => $ids,
        ], $params));
    }

    /**
     * Mass delete email templates. The filter params are merged into the body.
     *
     * @param  array  $ids  template ids
     * @param  bool  $all  delete every template matching $params
     * @param  array  $params  list filter params (merged into the body)
     */
    public function massDeletionEmailTemplates(array $ids, bool $all = false, array $params = []): Response
    {
        return $this->http->delete(self::TEMPLATES . '/letters/mass_deletion', array_merge([
            'all' => $all,
            'id' => $ids,
        ], $params));
    }

    /**
     * Get email newsletters.
     *
     * @param  array  $params  filter params
     */
    public function getEmailNewsletters(array $params = []): Response
    {
        return $this->http->get(self::NEWSLETTERS . '/mailings', $params);
    }

    /**
     * Get an email newsletter by id.
     *
     * @param  int|string  $id
     */
    public function getEmailNewsletter($id): Response
    {
        return $this->http->get(self::NEWSLETTERS . "/mailings/{$id}");
    }

    /**
     * Get the statistics of an email newsletter.
     *
     * @param  int|string  $id
     */
    public function getEmailNewsletterStatistics($id): Response
    {
        return $this->http->get(self::NEWSLETTERS . "/mailings/{$id}/statistics");
    }

    /**
     * Get the recipients of an email newsletter.
     *
     * @param  int|string  $id
     * @param  array  $params  recipients filter params
     */
    public function getEmailNewsletterRecipients($id, array $params = []): Response
    {
        return $this->http->get(self::NEWSLETTERS . "/mailings/{$id}/recipients", $params);
    }

    /**
     * Create an email newsletter.
     */
    public function createEmailNewsletter(array $data): Response
    {
        return $this->http->post(self::NEWSLETTERS . '/mailings', $data);
    }

    /**
     * Update an email newsletter.
     *
     * @param  int|string  $id
     */
    public function updateEmailNewsletter($id, array $data): Response
    {
        return $this->http->patch(self::NEWSLETTERS . "/mailings/{$id}", $data);
    }

    /**
     * Delete an email newsletter.
     *
     * @param  int|string  $id
     */
    public function deleteEmailNewsletter($id): Response
    {
        return $this->http->delete(self::NEWSLETTERS . "/mailings/{$id}");
    }

    /**
     * Send an email newsletter now.
     *
     * @param  int|string  $id
     */
    public function sendEmailNewsletter($id): Response
    {
        return $this->http->get(self::NEWSLETTERS . "/mailings/send/{$id}");
    }

    /**
     * Start the scheduled newsletter mailings.
     */
    public function startEmailNewsletterMailings(): Response
    {
        return $this->http->get(self::NEWSLETTERS . '/mailings/start');
    }

    /**
     * Get recipient counts by segments for a newsletter preset.
     *
     * @param  array  $presets  newsletter preset
     */
    public function getRecipientsCountsBySegments(array $presets): Response
    {
        return $this->http->post(self::NEWSLETTERS . '/mailings/recipients', ['presets' => $presets]);
    }

    /**
     * Get the remaining newsletter credits.
     */
    public function getEmailNewslettersCredits(): Response
    {
        return $this->http->get(self::NEWSLETTERS . '/mailings/credits');
    }

    /**
     * Mass send email newsletters. The filter params are merged into the body.
     *
     * @param  array  $ids  newsletter ids
     * @param  bool  $all  send every newsletter matching $params
     * @param  array  $params  list filter params (merged into the body)
     */
    public function massSendingEmailNewsletters(array $ids, bool $all = false, array $params = []): Response
    {
        return $this->http->post(self::NEWSLETTERS . '/mailings/mass_send', array_merge([
            'all' => $all,
            'ids' => $ids,
        ], $params));
    }

    /**
     * Mass delete email newsletters. The filter params are merged into the body.
     *
     * @param  array  $ids  newsletter ids
     * @param  bool  $all  delete every newsletter matching $params
     * @param  array  $params  list filter params (merged into the body)
     */
    public function massDeletionEmailNewsletters(array $ids, bool $all = false, array $params = []): Response
    {
        return $this->http->delete(self::NEWSLETTERS . '/mailings/mass_deletion', array_merge([
            'all' => $all,
            'id' => $ids,
        ], $params));
    }

    /**
     * Get sending domains.
     */
    public function getDomains(): Response
    {
        return $this->http->get(self::NEWSLETTERS . '/domains');
    }

    /**
     * Get a sending domain by id.
     *
     * @param  int|string  $id
     */
    public function getDomain($id): Response
    {
        return $this->http->get(self::NEWSLETTERS . "/domains/{$id}");
    }

    /**
     * Get the verification status of a sending domain.
     *
     * @param  int|string  $id
     */
    public function getDomainStatus($id): Response
    {
        return $this->http->get(self::NEWSLETTERS . "/domains/status/{$id}");
    }

    /**
     * Create a sending domain.
     */
    public function createDomain(array $data): Response
    {
        return $this->http->post(self::NEWSLETTERS . '/domains', $data);
    }

    /**
     * Delete a sending domain.
     *
     * @param  int|string  $id
     */
    public function deleteDomain($id): Response
    {
        return $this->http->delete(self::NEWSLETTERS . "/domains/{$id}");
    }

    /**
     * Get senders.
     */
    public function getSenders(): Response
    {
        return $this->http->get(self::NEWSLETTERS . '/senders');
    }

    /**
     * Get a sender by id.
     *
     * @param  int|string  $id
     */
    public function getSender($id): Response
    {
        return $this->http->get(self::NEWSLETTERS . "/senders/{$id}");
    }

    /**
     * Create a sender.
     */
    public function createSender(array $data): Response
    {
        return $this->http->post(self::NEWSLETTERS . '/senders', $data);
    }

    /**
     * Update a sender.
     *
     * @param  int|string  $id
     */
    public function updateSender($id, array $data): Response
    {
        return $this->http->patch(self::NEWSLETTERS . "/senders/{$id}", $data);
    }

    /**
     * Delete a sender.
     *
     * @param  int|string  $id
     */
    public function deleteSender($id): Response
    {
        return $this->http->delete(self::NEWSLETTERS . "/senders/{$id}");
    }
}
