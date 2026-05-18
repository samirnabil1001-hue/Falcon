<?php

namespace App\Enums;

enum FollowUpReason: string
{
    case WILL_REPLY_LATER = 'will_reply_later';
    case NEED_DETAILS = 'need_details';
    case COMMUNICATION_PROBLEM = 'communication_problem';
    case PREPARING_HIMSELF = 'preparing_himself';
    case SPECIAL_REQUEST = 'special_request';
    case OTHER = 'other'; // 

    public function label(): string
    {
        return match ($this) {
            self::WILL_REPLY_LATER => 'هيكلم / هيرد بعدين',
            self::NEED_DETAILS => 'عايز تفاصيل',
            self::COMMUNICATION_PROBLEM => 'مشكلة في التواصل',
            self::PREPARING_HIMSELF => 'بيجهز نفسه',
            self::SPECIAL_REQUEST => 'طلب خاص',
            self::OTHER => 'أخرى',
        };
    }
}