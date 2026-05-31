<?php

namespace App\Enums;

enum CompanyService: string
{
    case FLIGHTS = 'flights';
    case HOTEL_BOOKINGS = 'hotel_bookings';
    case TOURIST_VISAS = 'tourist_visas';
    case FREE_RESIDENCIES = 'free_residencies';
    case TRAVEL_PACKAGES = 'travel_packages';
    case TURKEY = 'turkey';
    case OTHERS = 'other';

    public function label(): string
    {
        return match ($this) {
            self::FLIGHTS => 'طيران',
            self::TOURIST_VISAS => 'تأشيرات سياحية',
            self::FREE_RESIDENCIES => 'إقامات حرة',
            self::HOTEL_BOOKINGS => 'حجز فنادق',
            self::TRAVEL_PACKAGES => 'برامج سياحية ',
            self::TURKEY => ' تشغيل عمالة',
            self::OTHERS => 'اخري',

        };
    }
}