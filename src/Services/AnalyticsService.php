<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Analytics service.
 *
 * Mirrors the JS SDK's AnalyticsService: reports and dashboards under the
 * `analytics-backend/v1` module, plus CRM funnel conversion under `/crm/v1`.
 */
class AnalyticsService extends Service
{
    private const REPORTS = '/analytics-backend/v1/reports';

    private const DASHBOARDS = '/analytics-backend/v1/dashboards';

    /**
     * Get the analytics reports list.
     *
     * @param  array  $params  filter params
     */
    public function getAnalyticsReportList(array $params = []): Response
    {
        return $this->http->get(self::REPORTS . '/', $params);
    }

    /**
     * Get an analytics report by id.
     *
     * @param  int|string  $id
     */
    public function getAnalyticReport($id): Response
    {
        return $this->http->get(self::REPORTS . "/{$id}");
    }

    /**
     * Create an analytics report.
     */
    public function createReport(array $data): Response
    {
        return $this->http->post(self::REPORTS . '/', $data);
    }

    /**
     * Update an analytics report.
     *
     * @param  int|string  $id
     */
    public function updateReport($id, array $data): Response
    {
        return $this->http->patch(self::REPORTS . "/{$id}", $data);
    }

    /**
     * Delete an analytics report.
     *
     * @param  int|string  $id
     */
    public function deleteReport($id): Response
    {
        return $this->http->delete(self::REPORTS . "/{$id}");
    }

    /**
     * Get the dashboards list.
     */
    public function getDashboardsLists(): Response
    {
        return $this->http->get(self::DASHBOARDS . '/');
    }

    /**
     * Get a dashboard by id.
     *
     * @param  int|string  $id
     */
    public function getDashboard($id): Response
    {
        return $this->http->get(self::DASHBOARDS . "/{$id}");
    }

    /**
     * Create a dashboard.
     */
    public function createDashboard(array $data): Response
    {
        return $this->http->post(self::DASHBOARDS . '/', $data);
    }

    /**
     * Update a dashboard.
     *
     * @param  int|string  $id
     */
    public function updateDashboard($id, array $data): Response
    {
        return $this->http->patch(self::DASHBOARDS . "/{$id}", $data);
    }

    /**
     * Delete a dashboard.
     *
     * @param  int|string  $id
     */
    public function deleteDashboard($id): Response
    {
        return $this->http->delete(self::DASHBOARDS . "/{$id}");
    }

    /**
     * Get CRM funnel conversion analytics.
     *
     * @param  array  $params  funnel conversion params
     */
    public function getFunnelConversion(array $params = []): Response
    {
        return $this->http->get('/crm/v1/analytics/funnels', $params);
    }
}
