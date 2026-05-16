<?php

namespace App\Enums;

enum PotentialCustomerStatus: string
{
    case NEW = 'new';
    case CONTACTED = 'contacted';
    case CANCELLED = 'cancelled';
    case CONFIRMED = 'confirmed';

    public function label(): string
    {
        return match($this) {
            self::NEW => 'جديد',
            self::CONTACTED => 'تم التواصل',
            self::CANCELLED => 'ملغي',
            self::CONFIRMED => 'مؤكد',
        };
    }
}