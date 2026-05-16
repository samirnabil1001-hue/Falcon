<?php

namespace App\Services;

use App\Models\PotentialCustomer;
use App\Enums\UserRole;
use App\Enums\PotentialCustomerStatus;
use App\Enums\PotentialCustomerSource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Enum;

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
            $query->where('added_by', $user->id);
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
            'status' => PotentialCustomerStatus::NEW, 
            'added_by' => $userId,
            'added_at' => now(),
        ]));
    }

    /**
     * تحديث عميل (شامل تحديث الحالة المنفصل)
     */
    public function update(PotentialCustomer $customer, array $data)
    {
        $validated = $this->validateData($data, false);
        $customer->update($validated);
        return $customer;
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
            // شروط صارمة عند الإدخال الجديد
            $rules = [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'source' => ['required', new Enum(PotentialCustomerSource::class)],
            ];
        } else {
            // 👈 تم الإصلاح هنا: الحقول تصبح اختيارية التمرير بـ sometimes عند التحديث لتقبل تغيير الحالة بمفردها
            $rules = [
                'name' => 'sometimes|required|string|max:255',
                'phone' => 'sometimes|required|string|max:20',
                'source' => ['sometimes', 'required', new Enum(PotentialCustomerSource::class)],
                'status' => ['sometimes', 'required', new Enum(PotentialCustomerStatus::class)],
            ];
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}