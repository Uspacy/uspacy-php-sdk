<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class ProfileServiceTest extends TestCase
{
    public function test_me_endpoints(): void
    {
        $this->sdk->profile()->getProfile();
        $this->assertRequestSent('GET', '/company/v1/users/me/');

        $this->sdk->profile()->getProfileOnlineStatus();
        $this->assertRequestSent('GET', '/company/v1/users/me/online');

        $this->sdk->profile()->getPortalSettings();
        $this->assertRequestSent('GET', '/company/v1/users/me/settings/');

        $this->sdk->profile()->updatePortalSettings(['theme' => 'dark']);
        $this->assertRequestSent('PATCH', '/company/v1/users/me/settings/', ['theme' => 'dark']);
    }

    public function test_two_factor(): void
    {
        $this->sdk->profile()->get2FaStatus();
        $this->assertRequestSent('GET', '/company/v1/users/me/twofa_status/');

        $this->sdk->profile()->enable2Fa();
        $this->assertRequestSent('PATCH', '/company/v1/users/me/twofa_enable/');

        $this->sdk->profile()->disable2Fa();
        $this->assertRequestSent('PATCH', '/company/v1/users/me/twofa_disable/');
    }

    public function test_requisites_and_templates(): void
    {
        $this->sdk->profile()->getRequisites();
        $this->assertRequestSent('GET', '/crm/v1/requisites/');

        $this->sdk->profile()->updateRequisite(9, ['name' => 'R']);
        $this->assertRequestSent('PATCH', '/crm/v1/requisites/9', ['name' => 'R']);

        $this->sdk->profile()->getTemplates(1, 20);
        $this->assertRequestSent('GET', '/crm/v1/requisites/templates', null, ['page' => 1, 'list' => 20]);

        $this->sdk->profile()->getBasicTemplates();
        $this->assertRequestSent('GET', '/crm/v1/requisites/templates/basic-templates');

        $this->sdk->profile()->getBankRequisitesById(9);
        $this->assertRequestSent('GET', '/crm/v1/requisites/9/bank_requisites/');
    }

    public function test_profile_fields(): void
    {
        $this->sdk->profile()->getProfileFields();
        $this->assertRequestSent('GET', '/company/v1/custom_fields/users/fields');

        $this->sdk->profile()->createProfileField(['name' => 'F']);
        $this->assertRequestSent('POST', '/company/v1/custom_fields/users/fields', ['name' => 'F']);

        $this->sdk->profile()->deleteProfileListValues('code1', 'v1');
        $this->assertRequestSent('DELETE', '/company/v1/custom_fields/users/lists/code1/v1');
    }
}
