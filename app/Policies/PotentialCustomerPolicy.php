<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PotentialCustomer;

class PotentialCustomerPolicy
{

    public function update(User $user, PotentialCustomer $potentialCustomer): bool
    {
        return $user->id === $potentialCustomer->user_id;
    }

 
    public function delete(User $user, PotentialCustomer $potentialCustomer): bool
    {
        return $user->id === $potentialCustomer->user_id;
    }
}