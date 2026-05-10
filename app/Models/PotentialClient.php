<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\PotentialClientStatus;

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
    protected function casts(): array
    {
        return [
            'status' => PotentialClientStatus::class,
        ];
    }
}