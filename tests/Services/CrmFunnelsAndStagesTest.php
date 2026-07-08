<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class CrmFunnelsAndStagesTest extends TestCase
{
    public function test_funnels_service_deals(): void
    {
        $this->sdk->crmDealsFunnels()->getFunnels();
        $this->assertRequestSent('GET', '/crm/v1/entities/deals/funnel');

        $this->sdk->crmDealsFunnels()->createFunnel(['title' => 'Main']);
        $this->assertRequestSent('POST', '/crm/v1/entities/deals/funnel', ['title' => 'Main']);

        $this->sdk->crmDealsFunnels()->updateFunnel(2, ['title' => 'M2']);
        $this->assertRequestSent('PATCH', '/crm/v1/entities/deals/funnel/2', ['title' => 'M2']);

        $this->sdk->crmDealsFunnels()->deleteFunnel(2);
        $this->assertRequestSent('DELETE', '/crm/v1/entities/deals/funnel/2');

        $this->sdk->crmDealsFunnels()->getStagesByFunnel(3);
        $this->assertRequestSent('GET', '/crm/v1/entities/deals/kanban/stage', null, ['funnel_id' => 3]);
    }

    public function test_funnels_service_leads_namespace(): void
    {
        $this->sdk->crmLeadsFunnels()->getFunnels();
        $this->assertRequestSent('GET', '/crm/v1/entities/leads/funnel');
    }

    public function test_funnel_reasons_use_shared_reasons_namespace(): void
    {
        $this->sdk->crmDealsFunnels()->createStageReason(4, ['name' => 'lost']);
        $this->assertRequestSent('POST', '/crm/v1/reasons/4', ['name' => 'lost']);

        $this->sdk->crmDealsFunnels()->updateStageReason(9, ['name' => 'x']);
        $this->assertRequestSent('PATCH', '/crm/v1/reasons/9', ['name' => 'x']);

        $this->sdk->crmDealsFunnels()->deleteStageReason(9);
        $this->assertRequestSent('DELETE', '/crm/v1/reasons/9');
    }

    public function test_stages_service(): void
    {
        $this->sdk->crmDealsStages()->getStages();
        $this->assertRequestSent('GET', '/crm/v1/entities/deals/kanban/stage');

        $this->sdk->crmDealsStages()->createStage(['name' => 'New']);
        $this->assertRequestSent('POST', '/crm/v1/entities/deals/kanban/stage', ['name' => 'New']);

        $this->sdk->crmDealsStages()->updateStage(6, ['name' => 'N2']);
        $this->assertRequestSent('PATCH', '/crm/v1/entities/deals/kanban/stage/6', ['name' => 'N2']);

        $this->sdk->crmDealsStages()->deleteStage(6);
        $this->assertRequestSent('DELETE', '/crm/v1/entities/deals/kanban/stage/6');
    }

    public function test_stages_service_reasons(): void
    {
        $this->sdk->crmLeadsStages()->getReasons(11);
        $this->assertRequestSent('GET', '/crm/v1/reasons/11');

        $this->sdk->crmLeadsStages()->createReason(11, ['name' => 'r']);
        $this->assertRequestSent('POST', '/crm/v1/reasons/11', ['name' => 'r']);
    }
}
