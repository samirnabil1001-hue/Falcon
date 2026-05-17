<?php

namespace App\Services;

use App\Models\PotentialCustomer;
use App\Models\CustomerFollowUp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerFollowUpService
{
    /**
     * جلب العملاء مع حساب عدد المتابعات وأحدث إجراء لكل عميل
     * (يستثني تماماً العملاء الذين ليس لديهم أي سجلات متابعة)
     */
    public function getPaginatedCustomers(int $perPage = 10): LengthAwarePaginator
    {
        return PotentialCustomer::has('followUps') 
            ->withCount('followUps')
            ->with(['followUps' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * تحديث حالة العميل وتسجيل المتابعة التاريخية في الـ Log
     */
    public function logFollowUp(int $customerId, array $data): PotentialCustomer
    {
        return DB::transaction(function () use ($customerId, $data) {
            $customer = PotentialCustomer::findOrFail($customerId);

            $customer->update([
                'status' => $data['status']
            ]);

            CustomerFollowUp::create([
                'potential_customer_id' => $customer->id,
                'user_id'               => Auth::id(), 
                'status'                => $data['status'],
                'reason'                => $data['reason'] ?? null,
                'next_follow_up_at'     => $data['next_follow_up_date'] ?? null,
                'notes'                 => $data['notes'] ?? null,
            ]);

            return $customer;
        });
    }
}