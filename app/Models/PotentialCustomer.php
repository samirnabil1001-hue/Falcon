<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PotentialCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'country_code', // Added your new column here
        'status',
        'source',
        'added_at',
        'user_id', 
    ];

    protected $casts = [
        'status' => \App\Enums\PotentialCustomerStatus::class,
        'source' => \App\Enums\PotentialCustomerSource::class,
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(CustomerFollowUp::class, 'potential_customer_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(PotentialCustomerService::class, 'potential_customer_id');
    }
}