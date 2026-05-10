<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PotentialClient extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'data_source',
        'service',
        'user_id',
        'status',
    ];
}