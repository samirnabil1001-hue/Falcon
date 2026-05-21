<?php

namespace App\Models;

use App\Enums\CompanyService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory; // 👈 استدعاء التريت لبناء الفاكتوري

class PotentialCustomerService extends Model
{
    use HasFactory; // 👈 تفعيل التريت داخل الكلاس

    /**
     * الحقول القابلة للتعبئة الجماعية (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'potential_customer_id',
        'user_id',
        'service_type',
        'notes',
    ];

    /**
     * تحويل أنواع البيانات تلقائياً (Casting).
     * هنا السحر! Laravel هيحول الـ string لـ Enum تلقائياً
     *
     * @var array<string, string>
     */
    protected $casts = [
        'service_type' => CompanyService::class,
    ];

  
    public function potentialCustomer(): BelongsTo
    {
        return $this->belongsTo(PotentialCustomer::class);
    }

  
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}