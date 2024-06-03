<?php

namespace App\Http\Integrations\Uspacy\Enums;

enum EntityTypes: string
{
    case LEAD = 'lead';
    case DEAL = 'deal';
    case CONTACT = 'contact';
    case COMPANY = 'company';
}
