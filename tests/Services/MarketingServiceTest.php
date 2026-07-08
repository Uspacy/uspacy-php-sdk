<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class MarketingServiceTest extends TestCase
{
    public function test_email_templates(): void
    {
        $this->sdk->marketing()->getEmailTemplates(['page' => 1]);
        $this->assertRequestSent('GET', '/marketing/v1/templates/letters', null, ['page' => 1]);

        $this->sdk->marketing()->getEmailTemplate(5);
        $this->assertRequestSent('GET', '/marketing/v1/templates/letters/5');

        $this->sdk->marketing()->createEmailTemplate(['name' => 'T']);
        $this->assertRequestSent('POST', '/marketing/v1/templates/letters', ['name' => 'T']);

        $this->sdk->marketing()->updateEmailTemplate(5, ['name' => 'T2']);
        $this->assertRequestSent('PATCH', '/marketing/v1/templates/letters/5', ['name' => 'T2']);

        $this->sdk->marketing()->deleteEmailTemplate(5);
        $this->assertRequestSent('DELETE', '/marketing/v1/templates/letters/5');
    }

    public function test_template_mass_ops_merge_params_into_body(): void
    {
        $this->sdk->marketing()->massEditingEmailTemplates([1, 2], ['active' => true], false, ['q' => 'x']);
        $this->assertRequestSent('POST', '/marketing/v1/templates/letters/mass_edit', [
            'all' => false,
            'payload' => ['active' => true],
            'id' => [1, 2],
            'q' => 'x',
        ]);

        $this->sdk->marketing()->massDeletionEmailTemplates([1], true, ['q' => 'y']);
        $this->assertRequestSent('DELETE', '/marketing/v1/templates/letters/mass_deletion', [
            'all' => true,
            'id' => [1],
            'q' => 'y',
        ]);
    }

    public function test_newsletters(): void
    {
        $this->sdk->marketing()->getEmailNewsletters(['page' => 1]);
        $this->assertRequestSent('GET', '/marketing/v1/newsletters/mailings', null, ['page' => 1]);

        $this->sdk->marketing()->getEmailNewsletterStatistics(5);
        $this->assertRequestSent('GET', '/marketing/v1/newsletters/mailings/5/statistics');

        $this->sdk->marketing()->sendEmailNewsletter(5);
        $this->assertRequestSent('GET', '/marketing/v1/newsletters/mailings/send/5');

        $this->sdk->marketing()->getRecipientsCountsBySegments(['segment' => 1]);
        $this->assertRequestSent('POST', '/marketing/v1/newsletters/mailings/recipients', ['presets' => ['segment' => 1]]);

        $this->sdk->marketing()->massSendingEmailNewsletters([1, 2], false, ['q' => 'z']);
        $this->assertRequestSent('POST', '/marketing/v1/newsletters/mailings/mass_send', ['all' => false, 'ids' => [1, 2], 'q' => 'z']);
    }

    public function test_domains_and_senders(): void
    {
        $this->sdk->marketing()->getDomainStatus(3);
        $this->assertRequestSent('GET', '/marketing/v1/newsletters/domains/status/3');

        $this->sdk->marketing()->createDomain(['domain' => 'x.com']);
        $this->assertRequestSent('POST', '/marketing/v1/newsletters/domains', ['domain' => 'x.com']);

        $this->sdk->marketing()->getSenders();
        $this->assertRequestSent('GET', '/marketing/v1/newsletters/senders');

        $this->sdk->marketing()->updateSender(4, ['name' => 'S']);
        $this->assertRequestSent('PATCH', '/marketing/v1/newsletters/senders/4', ['name' => 'S']);
    }
}
