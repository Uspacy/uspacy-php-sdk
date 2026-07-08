<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class GrowthServicesTest extends TestCase
{
    public function test_analytics_reports_and_dashboards(): void
    {
        $this->sdk->analytics()->getAnalyticsReportList(['page' => 1]);
        $this->assertRequestSent('GET', '/analytics-backend/v1/reports/', null, ['page' => 1]);

        $this->sdk->analytics()->getAnalyticReport('r1');
        $this->assertRequestSent('GET', '/analytics-backend/v1/reports/r1');

        $this->sdk->analytics()->createReport(['name' => 'R']);
        $this->assertRequestSent('POST', '/analytics-backend/v1/reports/', ['name' => 'R']);

        $this->sdk->analytics()->getDashboardsLists();
        $this->assertRequestSent('GET', '/analytics-backend/v1/dashboards/');

        $this->sdk->analytics()->updateDashboard('d1', ['title' => 'D']);
        $this->assertRequestSent('PATCH', '/analytics-backend/v1/dashboards/d1', ['title' => 'D']);
    }

    public function test_analytics_funnel_conversion_uses_crm_namespace(): void
    {
        $this->sdk->analytics()->getFunnelConversion(['funnel_id' => 3]);
        $this->assertRequestSent('GET', '/crm/v1/analytics/funnels', null, ['funnel_id' => 3]);
    }

    public function test_automations_workers_and_processes(): void
    {
        $this->sdk->automations()->getAutomations(['page' => 1]);
        $this->assertRequestSent('GET', '/automations-backend/v1/workers', null, ['page' => 1]);

        $this->sdk->automations()->toggleAutomation(5, ['active' => true]);
        $this->assertRequestSent('PATCH', '/automations-backend/v1/workers/5', ['active' => true]);

        $this->sdk->automations()->getWorkflows();
        $this->assertRequestSent('GET', '/automations-backend/v1/processes');

        $this->sdk->automations()->createWorkflow(['name' => 'W']);
        $this->assertRequestSent('POST', '/automations-backend/v1/processes', ['name' => 'W']);

        $this->sdk->automations()->updateWorkflow(9, ['name' => 'W2']);
        $this->assertRequestSent('PATCH', '/automations-backend/v1/processes/9', ['name' => 'W2']);
    }

    public function test_migrations_status_and_control(): void
    {
        $this->sdk->migrations()->getAllSystemsStatus();
        $this->assertRequestSent('GET', '/import/progress');

        $this->sdk->migrations()->getSystemProgress('bitrix24');
        $this->assertRequestSent('GET', '/import/progress', null, ['system' => 'bitrix24']);

        $this->sdk->migrations()->getMondayProgress('monday');
        $this->assertRequestSent('GET', '/progress/monday');

        $this->sdk->migrations()->getDataPresence();
        $this->assertRequestSent('POST', '/dataPresence/zoho');

        $this->sdk->migrations()->stopImport('trello');
        $this->assertRequestSent('POST', '/import/v1/trello/stop');

        $this->sdk->migrations()->stopImport('amo');
        $this->assertRequestSent('GET', '/import/stop', null, ['system' => 'amo']);
    }
}
