<?php

namespace App\Models;

use App\Enums\FollowUpReason;
use App\Enums\RejectionReason;
use App\Enums\PotentialCustomerStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerFollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'potential_customer_id',
        'user_id',
        'status',
        'reason',
        'next_follow_up_at',
        'notes',
    ];

    /**
     * تحويل التواريخ والحالات تلقائياً إلى كائنات برمجية
     */
    protected function casts(): array
    {
        return [
            'next_follow_up_at' => 'datetime',
            'status' => PotentialCustomerStatus::class,
        ];
    }

    /**
     * Dynamic Mutator: يتم استدعاؤها تلقائياً عند حفظ حقل الـ reason
     */
    public function setAttribute($key, $value)
    {
        if ($key === 'reason' && !is_null($value)) {
            if ($this->status === PotentialCustomerStatus::CANCELLED) {
                $this->attributes['reason'] = $value instanceof RejectionReason ? $value->value : $value;
                return $this;
            } else {
                $this->attributes['reason'] = $value instanceof FollowUpReason ? $value->value : $value;
                return $this;
            }
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Dynamic Accessor: يتم استدعاؤها تلقائياً عند جلب حقل الـ reason ليعود كـ Enum Object
     */
    public function getAttributeValue($key)
    {
        $value = parent::getAttributeValue($key);

        if ($key === 'reason' && !is_null($value)) {
            if ($this->status === PotentialCustomerStatus::CANCELLED) {
                return RejectionReason::tryFrom($value) ?? $value;
            } else {
                return FollowUpReason::tryFrom($value) ?? $value;
            }
        }

        return $value;
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