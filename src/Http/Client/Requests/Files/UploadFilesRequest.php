<?php

namespace Uspacy\SDK\Http\Client\Requests\Files;

use Saloon\Contracts\Body\HasBody;
use Saloon\Data\MultipartValue;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasMultipartBody;

class UploadFilesRequest extends Request implements HasBody
{
    use HasMultipartBody;

    /**
     * Define the HTTP method
     */
    protected Method $method = Method::POST;

    public function __construct(
        protected array $filePath,
        protected string $entityType,
        protected string $entityId
    ) {
    }

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/files/v1/files';
    }

    protected function defaultBody(): array
    {
        $multipartFiles = [];
        foreach ($this->filePath as $k => $file) {
            $multipartFiles['files[' . $k . ']'] = new MultipartValue(name: 'files[' . $k . ']', value: $file['data'], filename: $file['name']);
        }
        $multipartFiles['entityType'] = new MultipartValue(name: 'entityType', value: $this->entityType);
        $multipartFiles['entityId'] = new MultipartValue(name: 'entityId', value: $this->entityId);

        return $multipartFiles;
    }
}
