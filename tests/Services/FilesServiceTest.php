<?php

namespace Uspacy\SDK\Tests\Services;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Uspacy\SDK\Http\Client\Requests\Files\UploadFilesRequest;
use Uspacy\SDK\Tests\TestCase;

class FilesServiceTest extends TestCase
{
    public function test_get_files_with_params(): void
    {
        $this->sdk->files()->getFiles(['entityType' => 'deals']);

        $this->assertRequestSent('GET', '/files/v1/files', null, ['entityType' => 'deals']);
    }

    public function test_get_file_by_id(): void
    {
        $this->sdk->files()->getFileById(101);

        $this->assertRequestSent('GET', '/files/v1/files/101');
    }

    public function test_delete_file_by_id(): void
    {
        $this->sdk->files()->deleteFileById(101);

        $this->assertRequestSent('DELETE', '/files/v1/files/101');
    }

    public function test_delete_files_by_entity_sends_query(): void
    {
        $this->sdk->files()->deleteFilesByEntity('deals', 42);

        $this->assertRequestSent('DELETE', '/files/v1/files', null, ['entityType' => 'deals', 'entityId' => 42]);
    }

    public function test_upload_files_uses_multipart_request(): void
    {
        // Upload uses the dedicated multipart request, so give it its own mock.
        $this->sdk->withMockClient(new MockClient([
            UploadFilesRequest::class => MockResponse::make(['data' => []], 201),
        ]));

        $this->sdk->files()->uploadFiles(
            [['name' => 'a.txt', 'data' => 'hello']],
            'deals',
            '42',
        );

        $this->sdk->getMockClient()->assertSent(UploadFilesRequest::class);
    }
}
