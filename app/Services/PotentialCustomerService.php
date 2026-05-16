<?php

namespace App\Services;

use App\Models\PotentialCustomer;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PotentialCustomerService
{
    /**
     * جلب البيانات - تدعم الفلترة والـ Pagination
     */
    public function getPaginated($user, $perPage = 10)
    {
        $query = PotentialCustomer::with('creator');

        if ($user->role !== UserRole::CEO) {
            $query->where('added_by', $user->id);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * إنشاء عميل - مع التحقق وإسناد الحالة تلقائياً
     */
    public function store(array $data, int $userId)
    {
        $validated = $this->validateData($data);

        return PotentialCustomer::create(array_merge($validated, [
            'status'   => 'New', // إسناد الحالة تلقائياً هنا للعملاء الجدد
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
            'name'   => 'required|string|max:255',
            'phone'  => 'required|string|max:20',
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