<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Comments service.
 *
 * Covers the `comments/v1` module. Mirrors the Go SDK's `comments.go`.
 */
class CommentsService extends Service
{
    private const NAMESPACE = '/comments/v1';

    /**
     * Create a comment.
     */
    public function createComment(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/comments/', $data);
    }
}
