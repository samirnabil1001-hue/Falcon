<?php

namespace App\Enums;

enum CompanyService: string
{
    case FLIGHTS = 'flights';
    case TOURIST_VISAS = 'tourist_visas';
    case FREE_RESIDENCIES = 'free_residencies';
    case HOTEL_BOOKINGS = 'hotel_bookings';
    case TURKEY_TRAVEL_PACKAGES = 'turkey_travel_packages';
    case TRAVEL_PERMITS = 'travel_permits';

    public function label(): string
    {
        return match ($this) {
            self::FLIGHTS => 'طيران',
            self::TOURIST_VISAS => 'تأشيرات سياحية',
            self::FREE_RESIDENCIES => 'إقامات حرة',
            self::HOTEL_BOOKINGS => 'حجز فنادق',
            self::TURKEY_TRAVEL_PACKAGES => 'باقات سفر تركيا',
            self::TRAVEL_PERMITS => 'تصريح سفر',
        };
    }
}