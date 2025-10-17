<?php

namespace App\Http\Integrations\Uspacy\Requests\Messenger;

use Saloon\Enums\Method;
use Saloon\Http\Request;

use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;

class ReadAllMessages extends Request  implements HasBody
{
    use HasJsonBody;

    public function __construct(
        protected string $chatId,
        protected ?int $watermark = null,
    ) {}

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return "/messenger/v1/messages/readAll";
    }

    protected function defaultBody(): array
    {
        return [
            'chatId' => $this->chatId,
            'watermark' => $this->watermark
        ];
    }
}
