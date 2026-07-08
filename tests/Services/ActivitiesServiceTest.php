<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class ActivitiesServiceTest extends TestCase
{
    public function test_get_activities(): void
    {
        $this->sdk->activities()->getActivities(['page' => 1]);

        $this->assertRequestSent('GET', '/activities/v1/activities', null, ['page' => 1]);
    }

    public function test_create_activity(): void
    {
        $this->sdk->activities()->createActivity(['type' => 'call']);

        $this->assertRequestSent('POST', '/activities/v1/activities', ['type' => 'call']);
    }

    public function test_get_activity_by_id_uses_entities_path(): void
    {
        $this->sdk->activities()->getActivity(12);

        $this->assertRequestSent('GET', '/activities/v1/entities/12');
    }

    public function test_patch_activity(): void
    {
        $this->sdk->activities()->patchActivity(12, ['done' => true]);

        $this->assertRequestSent('PATCH', '/activities/v1/entities/12', ['done' => true]);
    }

    public function test_delete_activity(): void
    {
        $this->sdk->activities()->deleteActivity(12);

        $this->assertRequestSent('DELETE', '/activities/v1/entities/12');
    }

    public function test_mass_delete_activities_sends_body(): void
    {
        $this->sdk->activities()->massDeleteActivities(['ids' => [1, 2, 3]]);

        $this->assertRequestSent('DELETE', '/activities/v1/entities/mass_deletion', ['ids' => [1, 2, 3]]);
    }
}
