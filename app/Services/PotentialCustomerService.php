<?php

namespace App\Services;

use App\Models\PotentialCustomer;
use App\Enums\UserRole;
use Illuminate\Pagination\LengthAwarePaginator;

class PotentialCustomerService
{
  
    public function getPaginatedCustomers($user, int $perPage = 10): LengthAwarePaginator
    {
        $query = PotentialCustomer::with('creator');

        if ($user->role !== UserRole::CEO) {
            $query->where('added_by', $user->id);
        }

        return $query->latest()->paginate($perPage);
    }


    public function createCustomer(array $data, int $userId): PotentialCustomer
    {
        return PotentialCustomer::create(array_merge($data, [
            'added_by' => $userId,
            'added_at' => now(),
        ]));
    }
}