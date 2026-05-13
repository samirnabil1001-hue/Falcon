<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. تأكد من استدعاء المسار
use Illuminate\Database\Eloquent\Model;

class PotentialCustomer extends Model
{
    use HasFactory; // 2. تأكد من كتابة هذا السطر داخل الكلاس

    protected $fillable = [
        'name',
        'phone',
        'status',
        'source',
        'added_at',
        'added_by',
    ];

    // علاقة المستخدم الذي أضاف العميل
    public function creator()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}