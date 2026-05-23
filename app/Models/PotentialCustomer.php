<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PotentialCustomer extends Model
{
    use HasFactory;

    // تم تبديل user_id إلى user_id
    protected $fillable = [
        'name',
        'phone',
        'status',
        'source',
        'added_at',
        'user_id', 
    ];

    // تم تحديث الفتاح الأجنبي هنا ليكون user_id
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'status' => \App\Enums\PotentialCustomerStatus::class,
        'source' => \App\Enums\PotentialCustomerSource::class,
    ];

    public function followUps(): HasMany
    {
        return $this->hasMany(CustomerFollowUp::class, 'potential_customer_id');
    }

    /**
     * إذا كنت تريد ربط المستخدم بالعملاء المحتملين من جهة موديل الـ User،
     * فهذه الدالة مكانها الأصح في موديل User وليس هنا.
     * لكن إذا كنت بحاجتها هنا، فقد تم تحديث الفتاح الأجنبي فيها إلى user_id:
     */
    public function potentialCustomers(): HasMany
    {
        return $this->hasMany(PotentialCustomer::class, 'user_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(PotentialCustomerService::class, 'potential_customer_id');
    }
}