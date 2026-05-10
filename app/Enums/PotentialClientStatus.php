<?php

namespace App\Enums;

enum PotentialClientStatus: string
{
    case NEW = 'new';
    case CONTACTED = 'contacted';
    case PENDING = 'pending';
    case WAITING = 'waiting';
    case CONVERTED = 'converted';
}