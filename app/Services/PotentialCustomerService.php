<?php

namespace App\Services;

use App\Models\PotentialCustomer;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PotentialCustomerService
{
    /**
     * جلب البيانات - تدعم الفلترة، البحث، والترتيب الديناميكي مع الـ Pagination
     */
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
            $query->where('source', $filters['source']);
        }

        // 4. الفلترة حسب حالة العميل
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // 5. 📅 الفلترة بالنطاق الزمني لتاريخ الإضافة (Added At)
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
     * إنشاء عميل - مع التحقق وإسناد الحالة تلقائياً
     */
    public function store(array $data, int $userId)
    {
        $validated = $this->validateData($data);

        return PotentialCustomer::create(array_merge($validated, [
            'status' => 'New', 
            'added_by' => $userId,
            'added_at' => now(),
        ]));
    }

    /**
     * تحديث عميل
     */
    public function update(PotentialCustomer $customer, array $data)
    {
        // عند التحديث نقوم بالتحقق من البيانات القادمة فقط دون إجبارية الحالة إلا لو تم إرسالها
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
     * قواعد التحقق
     */
    protected function validateData(array $data, bool $isStore = true)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'source' => 'nullable|string|max:100',
        ];

        // لو كود التحديث يدعم إرسال الحالة، أضفها لقواعد التحقق
        if (!$isStore) {
            $rules['status'] = 'sometimes|required|string';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}