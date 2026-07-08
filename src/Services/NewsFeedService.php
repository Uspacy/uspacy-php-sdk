<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * News feed service.
 *
 * Covers the `newsfeed/v1` module. Mirrors the Go SDK's `newsfeeds.go`.
 */
class NewsFeedService extends Service
{
    private const NAMESPACE = '/newsfeed/v1';

    /**
     * Get a page of news feed posts.
     */
    public function getPosts(int $page = 1, int $list = 20, int $groupId = 0): Response
    {
        return $this->http->get(self::NAMESPACE . '/posts/', [
            'page' => $page,
            'list' => $list,
            'group_id' => $groupId,
        ]);
    }

    /**
     * Create a news feed post (form-encoded payload).
     */
    public function createPost(array $data): Response
    {
        return $this->http->postForm(self::NAMESPACE . '/posts', $data);
    }
}
