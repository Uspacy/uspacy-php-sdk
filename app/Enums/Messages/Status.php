<?php

namespace App\Enums\Messages;

enum Status: string
{
    case SENT = 'sent';

    case DELIVERED = 'delivered';

    case READ = 'read';

    case ERROR = 'error';
}
