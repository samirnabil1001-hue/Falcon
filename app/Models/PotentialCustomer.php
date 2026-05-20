<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    public function followUps(): HasMany
    {
        // البارامتر الثاني هو الفتاح الأجنبي الموجود في جدول الـ customer_follow_ups
        return $this->hasMany(CustomerFollowUp::class, 'potential_customer_id');
    }
    public function potentialCustomers()
    {
        // ربط المستخدم بالعملاء المحتملين بناءً على حقل added_by
        return $this->hasMany(PotentialCustomer::class, 'added_by');
    }
}