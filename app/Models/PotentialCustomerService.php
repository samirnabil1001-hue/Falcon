<?php

namespace App\Models;

use App\Enums\CompanyService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PotentialCustomerService extends Model
{
    protected $fillable = [
        'potential_customer_id',
        'user_id',
        'service_type',
        'notes',
    ];

    // هنا السحر! Laravel هيحول الـ string لـ Enum تلقائياً
    protected $casts = [
        'service_type' => CompanyService::class,
    ];

    // علاقة مع العميل
    public function potentialCustomer(): BelongsTo
    {
        return $this->belongsTo(PotentialCustomer::class);
    }

    // علاقة مع الموظف
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}