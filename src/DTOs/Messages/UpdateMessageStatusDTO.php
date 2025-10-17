<?php

namespace Uspacy\SDK\DTOs\Messages;

use Uspacy\SDK\Enums\Messages\Status;

class UpdateMessageStatusDTO
{
    public function __construct(
        public ?Status $status,
        public ?string $message
    ) {
    }
}
