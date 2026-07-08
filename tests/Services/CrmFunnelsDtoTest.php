<?php

namespace Uspacy\SDK\Tests\Services;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Uspacy\SDK\DTOs\Crm\FunnelDTO;
use Uspacy\SDK\DTOs\Crm\ReasonDTO;
use Uspacy\SDK\DTOs\Crm\ReasonsDTO;
use Uspacy\SDK\DTOs\Crm\StageDTO;
use Uspacy\SDK\Http\Client\Requests\GetRequest;
use Uspacy\SDK\Tests\TestCase;

class CrmFunnelsDtoTest extends TestCase
{
    public function test_get_funnels_hydrates_funnel_dtos_with_nested_stages(): void
    {
        $this->mockGet([
            [
                'id' => 1,
                'title' => 'Sales',
                'funnel_code' => 'sales',
                'default' => true,
                'active' => true,
                'tariff_limited' => false,
                'stages' => [
                    ['id' => 10, 'title' => 'New', 'stage_code' => 'new', 'system_stage' => true],
                ],
            ],
        ]);

        $funnels = $this->sdk->crmDealsFunnels()->getFunnels();

        $this->assertIsArray($funnels);
        $this->assertInstanceOf(FunnelDTO::class, $funnels[0]);
        $this->assertSame('sales', $funnels[0]->funnelCode);
        $this->assertTrue($funnels[0]->isDefault);
        $this->assertInstanceOf(StageDTO::class, $funnels[0]->stages[0]);
        $this->assertSame('new', $funnels[0]->stages[0]->stageCode);
    }

    public function test_get_stages_hydrates_from_data_envelope(): void
    {
        $this->mockGet([
            'data' => [
                ['id' => 10, 'title' => 'New', 'stage_code' => 'new', 'color' => '#fff', 'sort' => 1, 'system_stage' => false],
                ['id' => 11, 'title' => 'Won', 'stage_code' => 'won'],
            ],
        ]);

        $stages = $this->sdk->crmDealsStages()->getStages();

        $this->assertCount(2, $stages);
        $this->assertInstanceOf(StageDTO::class, $stages[0]);
        $this->assertSame('new', $stages[0]->stageCode);
        $this->assertSame('#fff', $stages[0]->color);
    }

    public function test_get_reasons_hydrates_grouped_success_fail(): void
    {
        $this->mockGet([
            'SUCCESS' => [['id' => 1, 'title' => 'Won deal', 'sort' => 1]],
            'FAIL' => [['id' => 2, 'title' => 'Too expensive'], ['id' => 3, 'title' => 'No budget']],
        ]);

        $reasons = $this->sdk->crmDealsStages()->getReasons(42);

        $this->assertInstanceOf(ReasonsDTO::class, $reasons);
        $this->assertCount(1, $reasons->success);
        $this->assertCount(2, $reasons->fail);
        $this->assertInstanceOf(ReasonDTO::class, $reasons->success[0]);
        $this->assertSame('Won deal', $reasons->success[0]->title);
        $this->assertSame('No budget', $reasons->fail[1]->title);
    }

    public function test_stage_custom_fields_via_get_has(): void
    {
        $this->mockGet(['data' => [['id' => 10, 'title' => 'New', 'customfield_1' => 'x']]]);

        $stage = $this->sdk->crmDealsStages()->getStages()[0];

        $this->assertTrue($stage->has('customfield_1'));
        $this->assertSame('x', $stage->get('customfield_1'));
    }

    public function test_empty_body_does_not_throw(): void
    {
        $this->sdk->withMockClient(new MockClient([
            GetRequest::class => MockResponse::make('', 204),
        ]));

        $this->assertSame([], $this->sdk->crmDealsFunnels()->getFunnels());
        $this->assertSame([], $this->sdk->crmDealsStages()->getStages());

        $reasons = $this->sdk->crmDealsStages()->getReasons(1);
        $this->assertSame([], $reasons->success);
        $this->assertSame([], $reasons->fail);
    }

    private function mockGet(array $payload): void
    {
        $this->sdk->withMockClient(new MockClient([
            GetRequest::class => MockResponse::make($payload, 200),
        ]));
    }
}
