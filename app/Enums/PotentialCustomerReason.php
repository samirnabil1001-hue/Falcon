<?php

namespace App\Enums;

enum PotentialCustomerReason: string
{
    case WILL_REPLY_LATER = 'will_reply_later';
    case NEED_DETAILS = 'need_details';
    case AGE_NOT_SUITABLE = 'age_not_suitable';
    case NOT_INTERESTED = 'not_interested';
    case COMMUNICATION_PROBLEM = 'communication_problem';
    case HAS_OBJECTION = 'has_objection';
    case PREPARING_HIMSELF = 'preparing_himself';
    case PRICE_OBJECTION = 'price_objection';
    case SPECIAL_REQUEST = 'special_request';
    case FOREIGN_NUMBER = 'foreign_number';

    public function label(): string
    {
        return match ($this) {
            self::WILL_REPLY_LATER => 'هيكلم / هيرد بعدين',
            self::NEED_DETAILS => 'عايز تفاصيل',
            self::AGE_NOT_SUITABLE => 'السن مش مناسب',
            self::NOT_INTERESTED => 'مش مهتم',
            self::COMMUNICATION_PROBLEM => 'مشكلة في التواصل',
            self::HAS_OBJECTION => 'عنده مانع',
            self::PREPARING_HIMSELF => 'بيجهز نفسه',
            self::PRICE_OBJECTION => 'اعتراض على السعر',
            self::SPECIAL_REQUEST => 'طلب خاص',
            self::FOREIGN_NUMBER => 'رقم أجنبي',
        };
    }
}