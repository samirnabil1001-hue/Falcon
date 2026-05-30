<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PotentialCustomer;
use Illuminate\Auth\Access\Response;

class PotentialCustomerPolicy
{
    public function view(User $user, PotentialCustomer $potentialCustomer): bool
    {
        return $user->isCEO() || $user->id === $potentialCustomer->user_id;
    }

    public function update(User $user, PotentialCustomer $potentialCustomer): bool
    {
        return $user->isCEO() || $user->id === $potentialCustomer->user_id;
    }

    public function updateStatus(User $user, PotentialCustomer $potentialCustomer): Response
    {
        
        if ($user->isCEO() || $user->id === $potentialCustomer->user_id) {
            return Response::allow();
        }

        return Response::deny('ليس لديك الصلاحية لتعديل حالة هذا العميل.');
    }

    public function delete(User $user, PotentialCustomer $potentialCustomer): bool
    {
        return $user->isCEO() || $user->id === $potentialCustomer->user_id;
    }

    public function updateAddedBy(User $user, PotentialCustomer $potentialCustomer): bool
    {
        return $user->isCEO();
    }
}