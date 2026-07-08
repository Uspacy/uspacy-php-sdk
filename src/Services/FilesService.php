<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\Http\Client\Requests\Files\UploadFilesRequest;

/**
 * Files service.
 *
 * Covers the `files/v1` module. Upload keeps the dedicated multipart request,
 * the rest go through the generic HTTP client. Mirrors the Go SDK's `files.go`.
 */
class FilesService extends Service
{
    private const NAMESPACE = '/files/v1';

    /**
     * Get the list of files.
     */
    public function getFiles(array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . '/files', $params);
    }

    /**
     * Get a single file by id.
     *
     * @param  int|string  $fileId
     */
    public function getFileById($fileId): Response
    {
        return $this->http->get(self::NAMESPACE . "/files/{$fileId}");
    }

    /**
     * Upload one or more files and attach them to an entity.
     *
     * @param  array  $files  list of ['name' => filename, 'data' => contents|resource]
     */
    public function uploadFiles(array $files, string $entityType, string $entityId): Response
    {
        return $this->http->connector()->send(new UploadFilesRequest($files, $entityType, $entityId));
    }

    /**
     * Delete a file by id.
     *
     * @param  int|string  $fileId
     */
    public function deleteFileById($fileId): Response
    {
        return $this->http->delete(self::NAMESPACE . "/files/{$fileId}");
    }

    /**
     * Delete all files attached to an entity.
     *
     * @param  int|string  $entityId
     */
    public function deleteFilesByEntity(string $entityType, $entityId): Response
    {
        return $this->http->delete(self::NAMESPACE . '/files', [], [
            'entityType' => $entityType,
            'entityId' => $entityId,
        ]);
    }
}
