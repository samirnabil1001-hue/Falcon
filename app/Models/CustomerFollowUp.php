<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory; // 👈 1. استدعاء الـ Trait هنا

class CustomerFollowUp extends Model
{
    use HasFactory; // 👈 2. استخدام الـ Trait داخل الكلاس هنا

    protected $fillable = [
        'potential_customer_id',
        'user_id',
        'status',
        'reason',
        'next_follow_up_at',
        'notes',
    ];

    /**
     * تحويل التواريخ تلقائياً إلى كائنات Carbon للتعامل معها برمجياً بسلاسة
     */
    protected function casts(): array
    {
        return [
            'next_follow_up_at' => 'datetime',
            'reason' => \App\Enums\PotentialCustomerReason::class,
            'status' => \App\Enums\PotentialCustomerStatus::class,
        ];
    }

    /**
     * العلاقة العكسية: كل سجل متابعة ينتمي إلى عميل محتمل واحد
     */
    public function potentialCustomer(): BelongsTo
    {
        return $this->belongsTo(PotentialCustomer::class, 'potential_customer_id');
    }

    /**
     * علاقة المتابعة مع الموظف الذي قام بالإجراء
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}