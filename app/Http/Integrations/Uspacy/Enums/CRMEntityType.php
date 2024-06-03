<?php

namespace App\Http\Integrations\Uspacy\Enums;

enum CRMEntityType: string
{
    case LEAD = 'leads';

    case DEAL = 'deals';

    case CONTRACT = 'contracts';

    case COMPANY = 'companies';
}
