<?php

namespace App\Services;

use App\Models\PotentialCustomer;
use App\Models\CustomerFollowUp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CustomerFollowUpService
{
   
    public function logFollowUp(int $customerId, array $data): PotentialCustomer
    {
        return DB::transaction(function () use ($customerId, $data) {
            $customer = PotentialCustomer::findOrFail($customerId);

            $customer->update([
                'status' => $data['status']
            ]);

            CustomerFollowUp::create([
                'potential_customer_id' => $customer->id,
                'user_id'               => Auth::id(), 
                'status'                => $data['status'],
                'reason'                => $data['reason'] ?? null,
                'next_follow_up_at'     => $data['next_follow_up_date'] ?? null,
                'notes'                 => $data['notes'] ?? null,
            ]);

            return $customer;
        });
    }
}