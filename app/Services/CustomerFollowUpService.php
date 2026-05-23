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
        $query = PotentialCustomer::query()
            ->where(function ($q) {
                $q->has('followUps')
                    ->orHas('services');
            })
            ->withCount([
                'followUps',
                'services as services_count'
            ])
            ->with([
                'followUps' => function ($query) {
                    $query->latest()->limit(1);
                },
                'services' => function ($query) {
                    $query->latest()->limit(1);
                }
            ]);

        // تطبيق البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('services', function ($s) use ($search) {
                        $s->where('service_type', 'like', "%{$search}%")
                            ->orWhere('notes', 'like', "%{$search}%");
                    });
            });
        }

        // تطبيق فلترة الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 👇 الفلترة الذكية للموظفين والمستخدم الحالي
        if ($request->get('my_clients') == '1') {
            // إذا تم تفعيل checkbox "عملائي" -> فلترة بالـ ID للمستخدم الحالي مباشرة
            $currentUserId = auth()->id();
            $query->where(function ($q) use ($currentUserId) {
                $q->whereHas('followUps', function ($f) use ($currentUserId) {
                    $f->where('user_id', $currentUserId);
                })->orWhereHas('services', function ($s) use ($currentUserId) {
                    $s->where('user_id', $currentUserId);
                });
            });
        } elseif ($request->filled('user_id')) {
            // إذا لم يُفعل خيار عملائي، نتحقق من اختيار موظف آخر من المنسدلة
            $userId = $request->user_id;
            $query->where(function ($q) use ($userId) {
                $q->whereHas('followUps', function ($f) use ($userId) {
                    $f->where('user_id', $userId);
                })->orWhereHas('services', function ($s) use ($userId) {
                    $s->where('user_id', $userId);
                });
            });
        }

        // تطبيق الفرز
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['name', 'created_at', 'follow_ups_count', 'services_count', 'status'];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
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
                'user_id' => Auth::id(),
                'status' => $data['status'],
                'reason' => $data['reason'] ?? null,
                'next_follow_up_at' => $data['next_follow_up_date'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            return $customer;
        });
    }
}