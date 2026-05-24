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

        // 👇 [جديد] تطبيق فلترة نطاق التاريخ (Date Range Filter)
        if ($request->filled('date_from')) {
            $query->whereHas('followUps', function ($f) use ($request) {
                $f->whereDate('created_at', '>=', $request->date_from);
            });
        }
        if ($request->filled('date_to')) {
            $query->whereHas('followUps', function ($f) use ($request) {
                $f->whereDate('created_at', '<=', $request->date_to);
            });
        }

        // الفلترة الذكية للموظفين والمستخدم الحالي
        if ($request->get('my_clients') == '1') {
            $currentUserId = auth()->id();
            $query->where(function ($q) use ($currentUserId) {
                $q->whereHas('followUps', function ($f) use ($currentUserId) {
                    $f->where('user_id', $currentUserId);
                })->orWhereHas('services', function ($s) use ($currentUserId) {
                    $s->where('user_id', $currentUserId);
                });
            });
        } elseif ($request->filled('user_id')) {
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
    public function logCustomerFollowUpsCount(Request $request)
    {
        $query = CustomerFollowUp::query()
            ->whereHas('potentialCustomer', function ($q) use ($request) {
                $q->where(function ($sub) {
                    $sub->has('followUps')->orHas('services');
                });

                // تطبيق البحث
                if ($request->filled('search')) {
                    $search = $request->search;
                    $q->where(function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhereHas('services', function ($s) use ($search) {
                                $s->where('service_type', 'like', "%{$search}%")
                                    ->orWhere('notes', 'like', "%{$search}%");
                            });
                    });
                }

                // تطبيق فلترة الحالة
                if ($request->filled('status')) {
                    $q->where('status', $request->status);
                }

                // الفلترة الذكية للموظفين والمستخدم الحالي
                if ($request->get('my_clients') == '1') {
                    $currentUserId = auth()->id();
                    $q->where(function ($sub) use ($currentUserId) {
                        $sub->whereHas('followUps', function ($f) use ($currentUserId) {
                            $f->where('user_id', $currentUserId);
                        })->orWhereHas('services', function ($s) use ($currentUserId) {
                            $s->where('user_id', $currentUserId);
                        });
                    });
                } elseif ($request->filled('user_id')) {
                    $userId = $request->user_id;
                    $q->where(function ($sub) use ($userId) {
                        $sub->whereHas('followUps', function ($f) use ($userId) {
                            $f->where('user_id', $userId);
                        })->orWhereHas('services', function ($s) use ($userId) {
                            $s->where('user_id', $userId);
                        });
                    });
                }
            });

        // 👇 [جديد] تطبيق فلترة التاريخ مباشرة على جدول المتابعات الفعلي لحساب الأعداد بدقة
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // مخرجات الدالة والـ Log دون أي تغيير في شكل الـ Response
        $statuses = $query->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get();

        error_log("--- Customer Follow-Ups Count by Status ---");

        $result = [];
        foreach ($statuses as $statusData) {
            $statusValue = is_object($statusData->status) ? $statusData->status->value : ($statusData->status ?? 'Unknown');

            error_log("Status: {$statusValue} | Total: {$statusData->total}");

            $result[$statusValue] = $statusData->total;
        }

        return $result;
    }
}