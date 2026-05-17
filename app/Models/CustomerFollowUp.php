<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerFollowUp extends Model
{
    protected $fillable = [
        'potential_customer_id',
        'user_id',
        'status',
        'reason',
        'next_follow_up_at',
        'notes',
    ];
}
