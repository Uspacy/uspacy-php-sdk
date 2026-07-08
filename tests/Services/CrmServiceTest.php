<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class CrmServiceTest extends TestCase
{
    public function test_get_entities_hits_the_typed_endpoint_with_query(): void
    {
        $this->sdk->crm()->getEntities('deals', ['page' => 2, 'list' => 20]);

        $this->assertRequestSent('GET', '/crm/v1/entities/deals/', null, ['page' => 2, 'list' => 20]);
    }

    public function test_convenience_getters_map_to_builtin_types(): void
    {
        $this->sdk->crm()->getContacts();
        $this->assertRequestSent('GET', '/crm/v1/entities/contacts/');

        $this->sdk->crm()->getLeads();
        $this->assertRequestSent('GET', '/crm/v1/entities/leads/');

        $this->sdk->crm()->getDeals();
        $this->assertRequestSent('GET', '/crm/v1/entities/deals/');
    }

    public function test_create_deal_posts_body_to_deals_endpoint(): void
    {
        $this->sdk->crm()->createDeal(['title' => 'New deal', 'amount' => 1000]);

        $this->assertRequestSent('POST', '/crm/v1/entities/deals/', ['title' => 'New deal', 'amount' => 1000]);
    }

    public function test_patch_entity_targets_a_single_record(): void
    {
        $this->sdk->crm()->patchEntity('deals', 42, ['amount' => 1500]);

        $this->assertRequestSent('PATCH', '/crm/v1/entities/deals/42', ['amount' => 1500]);
    }

    public function test_mass_edit_targets_the_mass_edit_endpoint(): void
    {
        $this->sdk->crm()->massEditEntities('deals', ['all' => true]);

        $this->assertRequestSent('PATCH', '/crm/v1/entities/deals/mass_edit', ['all' => true]);
    }

    /**
     * Regression: fields endpoints must NOT carry a trailing slash (PR review fix).
     */
    public function test_fields_endpoints_have_no_trailing_slash(): void
    {
        $this->sdk->crm()->getFields('deals');
        $this->assertRequestSent('GET', '/crm/v1/entities/deals/fields');

        $this->sdk->crm()->getField('deals', 'priority');
        $this->assertRequestSent('GET', '/crm/v1/entities/deals/fields/priority');

        $this->sdk->crm()->createField('deals', ['name' => 'Priority']);
        $this->assertRequestSent('POST', '/crm/v1/entities/deals/fields', ['name' => 'Priority']);

        $this->sdk->crm()->deleteField('deals', 'priority');
        $this->assertRequestSent('DELETE', '/crm/v1/entities/deals/fields/priority');
    }

    public function test_funnel_stage_by_funnel_id_sends_query(): void
    {
        $this->sdk->crm()->getFunnelStagesByFunnelId('deals', 3);

        $this->assertRequestSent('GET', '/crm/v1/entities/deals/kanban/stage/', null, ['funnel_id' => 3]);
    }

    public function test_move_funnel_stage_builds_the_move_endpoint(): void
    {
        $this->sdk->crm()->moveFunnelStage('deals', 42, 'stage-7', ['reason' => 'won']);

        $this->assertRequestSent('POST', '/crm/v1/entities/deals/42/move/stage/stage-7', ['reason' => 'won']);
    }

    public function test_products_and_calls(): void
    {
        $this->sdk->crm()->getProduct(9);
        $this->assertRequestSent('GET', '/crm/v1/static/products/9');

        $this->sdk->crm()->createCall(['direction' => 'inbound']);
        $this->assertRequestSent('POST', '/crm/v1/events/call', ['direction' => 'inbound']);
    }
}
