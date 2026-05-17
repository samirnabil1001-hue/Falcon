<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * تحويل التواريخ تلقائياً إلى كائنات Carbon للتعامل معها برمجياً بسلاسة
     */
    protected function casts(): array
    {
        return [
            'next_follow_up_at' => 'datetime',
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
     * علاقة المتابعة مع الموظف الذي قام بالإجراء (إذا احتجت عرض اسم الموظف في الـ Log)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}