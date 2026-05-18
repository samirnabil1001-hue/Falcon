<?php

namespace App\Enums;

enum PotentialCustomerSource: string
{
    case FACEBOOK = 'Facebook';
    case INSTAGRAM = 'Instagram';
    case WEBSITE = 'Website';
    case WHATSAPP = 'WhatsApp';
    case REFERRAL = 'Referral';
    case GOOGLE_ADS = 'Google Ads'; 
    case OTHER = 'Other';

    public function label(): string
    {
        return match($this) {
            self::FACEBOOK => 'فيسبوك',
            self::INSTAGRAM => 'إنستغرام',
            self::WEBSITE => 'الموقع الإلكتروني',
            self::WHATSAPP => 'واتساب',
            self::REFERRAL => 'ترشيح / عميل سابق',
            self::GOOGLE_ADS => 'إعلانات جوجل',
            self::OTHER => 'أخرى',
        };
    }
}