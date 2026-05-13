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
    public function list($user, $perPage = 10)
    {
        $query = PotentialCustomer::with('creator');

        if ($user->role !== UserRole::CEO) {
            $query->where('added_by', $user->id);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * إنشاء عميل - مع التحقق
     */
    public function store(array $data, int $userId)
    {
        $validated = $this->validateData($data);

        return PotentialCustomer::create(array_merge($validated, [
            'added_by' => $userId,
            'added_at' => now(),
        ]));
    }

    /**
     * تحديث عميل
     */
    public function update(PotentialCustomer $customer, array $data)
    {
        $validated = $this->validateData($data);
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
     * قواعد التحقق - موحدة للـ Web والـ API
     */
    protected function validateData(array $data)
    {
        $validator = Validator::make($data, [
            'name'   => 'required|string|max:255',
            'phone'  => 'required|string|max:20',
            'source' => 'nullable|string|max:100',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}