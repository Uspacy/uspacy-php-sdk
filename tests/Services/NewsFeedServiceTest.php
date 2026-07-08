<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class NewsFeedServiceTest extends TestCase
{
    /**
     * Regression: an unset group filter must be omitted, not sent as group_id=0 (PR review fix).
     */
    public function test_get_posts_omits_group_id_when_not_provided(): void
    {
        $this->sdk->newsFeed()->getPosts(page: 1, list: 20);

        $this->assertRequestSent('GET', '/newsfeed/v1/posts/', null, ['page' => 1, 'list' => 20]);
    }

    public function test_get_posts_includes_group_id_when_provided(): void
    {
        $this->sdk->newsFeed()->getPosts(page: 1, list: 20, groupId: 5);

        $this->assertRequestSent('GET', '/newsfeed/v1/posts/', null, ['page' => 1, 'list' => 20, 'group_id' => 5]);
    }

    public function test_create_post_is_form_encoded(): void
    {
        $this->sdk->newsFeed()->createPost(['message' => 'Hello']);

        $this->assertRequestSent('POST', '/newsfeed/v1/posts', ['message' => 'Hello']);
    }
}
