<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PotentialCustomer;

class PotentialCustomerPolicy
{
    /**
     * التحقق من صلاحية التحديث العام
     */
    public function update(User $user, PotentialCustomer $potentialCustomer): bool
    {
        // تم التعديل من user_id إلى added_by
        return $user->id === $potentialCustomer->added_by; 
    }

    /**
     * التحقق من صلاحية تحديث الحالة فقط
     */
    public function updateStatus(User $user, PotentialCustomer $potentialCustomer): bool
    {
        return $user->id === $potentialCustomer->added_by;
    }

    /**
     * التحقق من صلاحية الحذف
     */
    public function delete(User $user, PotentialCustomer $potentialCustomer): bool
    {
        return $user->id === $potentialCustomer->added_by;
    }
}