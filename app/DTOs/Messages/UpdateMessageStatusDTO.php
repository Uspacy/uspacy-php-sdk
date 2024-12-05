<?php

namespace App\DTOs\Messages;

use App\Enums\Messages\Status;

class UpdateMessageStatusDTO
{
    public function __construct(
        public ?Status $status,
        public ?string $message
    ) {
    }
}