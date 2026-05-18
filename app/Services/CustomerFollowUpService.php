<?php

namespace App\Services;

use App\Models\PotentialCustomer;
use App\Models\CustomerFollowUp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class CustomerFollowUpService
{
    /**
     * جلب العملاء مع حساب عدد المتابعات وأحدث إجراء لكل عميل
     * مع دعم البحث والفلترة والفرز
     */
    public function getPaginatedCustomers(Request $request, int $perPage = 10): LengthAwarePaginator
    {
        $query = PotentialCustomer::has('followUps')
            ->withCount('followUps')
            ->with(['followUps' => function ($query) {
                $query->latest()->limit(1);
            }]);

        // تطبيق البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // تطبيق فلترة الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // تطبيق الفرز
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // التحقق من صحة أعمدة الفرز
        $allowedSorts = ['name', 'created_at', 'follow_ups_count', 'status'];
        if (in_array($sortBy, $allowedSorts)) {
            if ($sortBy === 'follow_ups_count') {
                $query->orderBy('follow_ups_count', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->latest();
        }

        return $query->paginate($perPage);
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