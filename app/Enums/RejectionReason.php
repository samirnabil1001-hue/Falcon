<?php

namespace App\Enums;

enum RejectionReason: string
{
    case AGE_NOT_SUITABLE = 'age_not_suitable';
    case NOT_INTERESTED = 'not_interested';
    case HAS_OBJECTION = 'has_objection';
    case PRICE_OBJECTION = 'price_objection';
    case FOREIGN_NUMBER = 'foreign_number';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::AGE_NOT_SUITABLE => 'السن مش مناسب',
            self::NOT_INTERESTED => 'مش مهتم',
            self::HAS_OBJECTION => 'عنده مانع',
            self::PRICE_OBJECTION => 'اعتراض على السعر',
            self::FOREIGN_NUMBER => 'رقم أجنبي',
            self::OTHER => 'أخرى',
        };
    }
}