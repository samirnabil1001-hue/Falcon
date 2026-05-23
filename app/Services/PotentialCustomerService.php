<?php

namespace App\Services;

use App\Models\PotentialCustomer;
use App\Models\CustomerFollowUp; // 👈 تم الاستيراد هنا
use App\Enums\UserRole;
use App\Enums\PotentialCustomerStatus;
use App\Enums\PotentialCustomerSource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\DB;     // 👈 تم الاستيراد هنا
use Illuminate\Support\Facades\Auth;   // 👈 تم الاستيراد هنا

class PotentialCustomerService
{
    /**
     * جلب البيانات - تدعم الفلترة، البحث، النطاق الزمني، والترتيب الديناميكي
     */
    public function getPaginated($user, array $filters = [], $perPage = 10)
    {
        $query = PotentialCustomer::with('creator');

        // 1. صلاحيات العرض
        if ($user->role !== UserRole::CEO) {
            $query->where('user_id', $user->id);
        }

        // 2. فلترة البحث بالاسم أو الهاتف
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // 3. الفلترة حسب مصدر العميل
        if (!empty($filters['source'])) {
            $source = $filters['source'] instanceof PotentialCustomerSource
                ? $filters['source']->value
                : $filters['source'];
            $query->where('source', $source);
        }

        // 4. الفلترة حسب حالة العميل
        if (!empty($filters['status'])) {
            $status = $filters['status'] instanceof PotentialCustomerStatus
                ? $filters['status']->value
                : $filters['status'];
            $query->where('status', $status);
        }

        // 5. الفلترة بالنطاق الزمني لتاريخ الإضافة
        if (!empty($filters['date_from'])) {
            $query->whereDate('added_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('added_at', '<=', $filters['date_to']);
        }

        // 6. الترتيب الديناميكي الآمن
        $sortBy = $filters['sort_by'] ?? 'added_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';

        $allowedSortFields = ['name', 'source', 'status', 'added_at'];
        $allowedSortOrders = ['asc', 'desc'];

        if (in_array($sortBy, $allowedSortFields) && in_array(strtolower($sortOrder), $allowedSortOrders)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest('added_at');
        }

        return $query->paginate($perPage);
    }

    /**
     * إنشاء عميل - مع التحقق وإسناد الحالة تلقائياً كـ Enum
     */
    public function store(array $data, int $userId)
    {
        $validated = $this->validateData($data, true);

        return PotentialCustomer::create(array_merge($validated, [
            'status' => PotentialCustomerStatus::NEW ,
            'user_id' => $userId,
            'added_at' => now(),
        ]));
    }

    /**
     * تحديث عميل العام (بيانات شخصية أو تعديل عادي)
     */
    public function update(PotentialCustomer $customer, array $data)
    {
        $validated = $this->validateData($data, false);
        $customer->update($validated);
        return $customer;
    }

    /**
     * 👈 دالة تحديث الحالة المتقدمة مع تسجيل المتابعة (Log)
     */
    public function updateStatusAndLogFollowUp(PotentialCustomer $customer, array $data, int $userId): PotentialCustomer
    {
        $validated = $this->validateData($data, false);

        return DB::transaction(function () use ($customer, $validated, $userId) {
            $customer->update([
                'status' => $validated['status']
            ]);

            CustomerFollowUp::create([
                'potential_customer_id' => $customer->id,
                'user_id' => $userId,
                'status' => $validated['status'],
                'reason' => $validated['reason'] ?? null,
                'next_follow_up_at' => $validated['next_follow_up_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            if ($validated['status'] === \App\Enums\PotentialCustomerStatus::CONFIRMED->value || $validated['status'] === \App\Enums\PotentialCustomerStatus::CONFIRMED) {

                \App\Models\PotentialCustomerService::create([
                    'potential_customer_id' => $customer->id,
                    'user_id' => $userId,
                    'service_type' => $validated['service_type'], // لازم تتبعت من الفورم (مثل: flights, tourist_visas)
                    'notes' => $validated['service_notes'] ?? $validated['notes'] ?? null, // ملاحظات الخدمة
                ]);
            }

            return $customer;
        });
    }

    /**
     * حذف عميل
     */
    public function delete(PotentialCustomer $customer)
    {
        return $customer->delete();
    }

    /**
     * قواعد التحقق الذكية والمرنة
     */
    protected function validateData(array $data, bool $isStore = true)
    {
        if ($isStore) {
            $rules = [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'source' => ['required', new Enum(PotentialCustomerSource::class)],
            ];
        } else {
            $rules = [
                'name' => 'sometimes|required|string|max:255',
                'phone' => 'sometimes|required|string|max:20',
                'source' => ['sometimes', 'required', new Enum(PotentialCustomerSource::class)],
                'status' => ['sometimes', 'required', new Enum(PotentialCustomerStatus::class)],
                'reason' => 'nullable|string',
                'next_follow_up_date' => 'nullable|date',
                'notes' => 'nullable|string',

                // 💡 أضف هذه السطور هنا للسماح بمرور حقول الخدمة المستهدفة
                'service_type' => ['sometimes', 'required', new Enum(\App\Enums\CompanyService::class)],
                'service_notes' => 'nullable|string',
            ];
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
    /**
     * جلب أحدث 5 عملاء فقط لحالة معينة بناءً على صلاحيات المستخدم المحددة
     */
    public function getLatestUrgentByStatus($user, string $status, $limit = 5)
    {
        $query = PotentialCustomer::where('status', $status);

        // قيود الصلاحيات الخاصة بك
        if ($user->role !== UserRole::CEO) {
            $query->where('user_id', $user->id);
        }

        // 💡 التعديل هنا: استخدام oldest لترتيب المضاف أولاً (تاريخ أقدم) وتحديد العدد بـ 5
        return $query->oldest('added_at')
            ->take($limit)
            ->get();
    }
}