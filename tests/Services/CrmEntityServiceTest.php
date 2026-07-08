<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class CrmEntityServiceTest extends TestCase
{
    public function test_deals_and_leads_are_scoped_to_their_namespace(): void
    {
        $this->sdk->crmDeals()->getEntities(['page' => 1]);
        $this->assertRequestSent('GET', '/crm/v1/entities/deals', null, ['page' => 1]);

        $this->sdk->crmLeads()->getEntities();
        $this->assertRequestSent('GET', '/crm/v1/entities/leads');

        $this->sdk->crmContacts()->getEntities();
        $this->assertRequestSent('GET', '/crm/v1/entities/contacts');

        $this->sdk->crmCompanies()->getEntities();
        $this->assertRequestSent('GET', '/crm/v1/entities/companies');
    }

    public function test_crud(): void
    {
        $this->sdk->crmDeals()->createEntity(['title' => 'D']);
        $this->assertRequestSent('POST', '/crm/v1/entities/deals', ['title' => 'D']);

        $this->sdk->crmDeals()->updateEntity(7, ['title' => 'D2']);
        $this->assertRequestSent('PATCH', '/crm/v1/entities/deals/7', ['title' => 'D2']);

        $this->sdk->crmDeals()->deleteEntity(7);
        $this->assertRequestSent('DELETE', '/crm/v1/entities/deals/7');
    }

    public function test_mass_deletion_sends_wrapped_body_and_query(): void
    {
        $this->sdk->crmDeals()->massDeletion([1, 2], [3], false, ['q' => 'x']);

        $this->assertRequestSent(
            'DELETE',
            '/crm/v1/entities/deals/mass_deletion',
            ['all' => false, 'entity_ids' => [1, 2], 'except_ids' => [3]],
            ['q' => 'x'],
        );
    }

    public function test_mass_editing_sends_payload_and_settings(): void
    {
        $this->sdk->crmDeals()->massEditing(['stage' => 'won'], ['notify' => true], [1], [], true);

        $this->assertRequestSent(
            'PATCH',
            '/crm/v1/entities/deals/mass_edit',
            ['all' => true, 'entity_ids' => [1], 'except_ids' => [], 'payload' => ['stage' => 'won'], 'settings' => ['notify' => true]],
        );
    }

    public function test_fields_and_lists(): void
    {
        $this->sdk->crmDeals()->getFields();
        $this->assertRequestSent('GET', '/crm/v1/entities/deals/fields');

        $this->sdk->crmDeals()->createField(['name' => 'X']);
        $this->assertRequestSent('POST', '/crm/v1/entities/deals/fields', ['name' => 'X']);

        $this->sdk->crmDeals()->updateField('code1', ['name' => 'Y']);
        $this->assertRequestSent('PATCH', '/crm/v1/entities/deals/fields/code1', ['name' => 'Y']);

        $this->sdk->crmDeals()->deleteField('code1');
        $this->assertRequestSent('DELETE', '/crm/v1/entities/deals/fields/code1');

        $this->sdk->crmDeals()->updateListValues('code1', [['value' => 'a']]);
        $this->assertRequestSent('POST', '/crm/v1/entities/deals/lists/code1', [['value' => 'a']]);

        $this->sdk->crmDeals()->deleteListValue('code1', 'val1');
        $this->assertRequestSent('DELETE', '/crm/v1/entities/deals/lists/code1/val1');
    }

    public function test_stage_helpers(): void
    {
        $this->sdk->crmLeads()->getByStage(5);
        $this->assertRequestSent('GET', '/crm/v1/entities/leads/kanban/stage/5');

        $this->sdk->crmDeals()->moveFromStageToStage(7, 9, 3);
        $this->assertRequestSent('POST', '/crm/v1/entities/deals/7/move/stage/9', ['reason_id' => 3]);
    }
}
