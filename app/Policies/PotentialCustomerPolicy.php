<?php

namespace App\Policies;

use App\Models\PotentialCustomer;
use App\Models\User;
use App\Enums\UserRole;

class PotentialCustomerPolicy
{
  
    public function view(User $user, PotentialCustomer $potentialCustomer): bool
    {
        return $user->role === UserRole::CEO || $user->id === $potentialCustomer->added_by;
    }

    public function update(User $user, PotentialCustomer $potentialCustomer): bool
    {
        return $user->role === UserRole::CEO || $user->id === $potentialCustomer->added_by;
    }

    public function delete(User $user, PotentialCustomer $potentialCustomer): bool
    {
        // ربما نريد أن الحذف يكون للـ CEO فقط أو صاحب السجل
        return $user->role === UserRole::CEO || $user->id === $potentialCustomer->added_by;
    }
}