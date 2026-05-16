<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotentialCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'status',
        'source',
        'added_at',
        'added_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
    protected $casts = [
        'status' => \App\Enums\PotentialCustomerStatus::class,
        'source' => \App\Enums\PotentialCustomerSource::class,
    ];
}