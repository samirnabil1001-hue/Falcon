<?php

namespace App\Enums;

enum UserRole: string
{
    case CEO = 'CEO';
    case TEAM_LEAD = 'TeamLead';
    case AGENT = 'Agent';
    case NORMAL = 'normal';
}