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
        // البدء بالاستعلام وجلب الكاونت للمتابعات والخدمات معاً بأداء سريع
        $query = PotentialCustomer::query()
            ->where(function ($q) {
                $q->has('followUps')
                    ->orHas('services'); // 👈 يرجع العميل لو عنده متابعة أو لو عنده خدمة
            })
            ->withCount([
                'followUps',
                'services as services_count' // 👈 هيديك كاونت جاهز لعدد المرات في الجدول باسم services_count
            ])
            ->with([
                'followUps' => function ($query) {
                    $query->latest()->limit(1);
                },
                // جلب أحدث خدمة تم طلبها للعميل لعرض بياناتها مباشرة
                'services' => function ($query) {
                    $query->latest()->limit(1);
                }
            ]);

        // تطبيق البحث (على العميل، المتابعات، أو الخدمات)
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

        // تطبيق الفرز والتأكد من الأعمدة المسموحة
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