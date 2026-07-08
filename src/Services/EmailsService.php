<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;

/**
 * Emails service.
 *
 * Covers the `email/v1` module: folders, mailboxes and letters.
 * Mirrors the Go SDK's `emails.go`.
 */
class EmailsService extends Service
{
    private const NAMESPACE = '/email/v1';

    /**
     * Get all mail folders.
     */
    public function getFolders(): Response
    {
        return $this->http->get(self::NAMESPACE . '/folders');
    }

    /**
     * Create a mail folder.
     */
    public function createFolder(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/folders', $data);
    }

    /**
     * Get all mailboxes.
     */
    public function getMailboxes(): Response
    {
        return $this->http->get(self::NAMESPACE . '/emails');
    }

    /**
     * Create a letter inside a folder.
     *
     * @param  int|string  $folderId
     */
    public function createLetterByFolder($folderId, array $data): Response
    {
        return $this->http->post(self::NAMESPACE . "/letters/by_folder/{$folderId}", $data);
    }

    /**
     * Delete a letter by id.
     *
     * @param  int|string  $letterId
     */
    public function deleteLetter($letterId): Response
    {
        return $this->http->delete(self::NAMESPACE . "/letters/{$letterId}");
    }
}
