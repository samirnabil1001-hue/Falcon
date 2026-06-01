<?php

namespace App\Services;

use App\Models\PotentialCustomer;
use App\Models\CustomerFollowUp;
use App\Models\PotentialCustomerService as ServiceModel; 
use App\Enums\UserRole;
use App\Enums\PotentialCustomerStatus;
use App\Enums\PotentialCustomerSource;
use App\Enums\CompanyService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\DB;

class PotentialCustomerService
{
   public function getPaginated($user, array $filters = [], $perPage = 10)
    {
        $query = PotentialCustomer::with('creator');

        // Role restriction: Non-CEOs can only see their own records
        if ($user->role !== UserRole::CEO) {
            $query->where('user_id', $user->id);
        } else {
            // CEO restriction: Can filter by a specific user_id if provided
            if (!empty($filters['user_id'])) {
                $query->where('user_id', $filters['user_id']);
            }
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['source'])) {
            $source = $filters['source'] instanceof PotentialCustomerSource ? $filters['source']->value : $filters['source'];
            $query->where('source', $source);
        }

        if (!empty($filters['status'])) {
            $status = $filters['status'] instanceof PotentialCustomerStatus ? $filters['status']->value : $filters['status'];
            $query->where('status', $status);
        }

        if (!empty($filters['date_from'])) $query->whereDate('added_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('added_at', '<=', $filters['date_to']);

        $sortBy = $filters['sort_by'] ?? 'added_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';

        if (in_array($sortBy, ['name', 'source', 'status', 'added_at']) && in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest('added_at');
        }

        return $query->paginate($perPage);
    }

    public function store(array $data, int $userId)
    {
        $validated = $this->validateData($data, true);

        return PotentialCustomer::create(array_merge($validated, [
            'status' => PotentialCustomerStatus::NEW,
            'user_id' => $userId,
            'added_at' => now(),
        ]));
    }

    public function update(PotentialCustomer $customer, array $data)
    {
        $validated = $this->validateData($data, false);
        $customer->update($validated);
        return $customer;
    }

    public function updateStatusAndLogFollowUp(PotentialCustomer $customer, array $data, int $userId): PotentialCustomer
    {
        $validated = $this->validateData($data, false);

        return DB::transaction(function () use ($customer, $validated, $userId) {
            $customer->update([
                'status' => $validated['status']
            ]);

            CustomerFollowUp::create([
                'potential_customer_id' => $customer->id,
                'user_id' => $userId,
                'status' => $validated['status'],
                'reason' => $validated['reason'] ?? null,
                'next_follow_up_at' => $validated['next_follow_up_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            if ($validated['status'] === PotentialCustomerStatus::CONFIRMED->value) {
                ServiceModel::create([
                    'potential_customer_id' => $customer->id,
                    'user_id' => $userId,
                    'service_type' => $validated['service_type'] ?? null,
                    'notes' => $validated['service_notes'] ?? $validated['notes'] ?? null,
                ]);
            }

            return $customer;
        });
    }

    public function delete(PotentialCustomer $customer)
    {
        return $customer->delete();
    }

    protected function validateData(array $data, bool $isStore = true)
    {
        $rules = [
            'name'         => $isStore ? 'required|string|max:255' : 'sometimes|required|string|max:255',
            'phone'        => $isStore ? 'required|string|max:20' : 'sometimes|required|string|max:20',
            'country_code' => $isStore ? 'required|string|max:10' : 'sometimes|required|string|max:10',
            'source'       => ['sometimes', 'required', new Enum(PotentialCustomerSource::class)],
        ];

        if (!$isStore) {
            $rules = array_merge($rules, [
                'status'        => ['sometimes', 'required', new Enum(PotentialCustomerStatus::class)],
                'reason'        => 'nullable|string',
                'next_follow_up_date' => 'nullable|date',
                'notes'         => 'nullable|string',
                'service_type'  => ['sometimes', 'required', new Enum(CompanyService::class)],
                'service_notes' => 'nullable|string',
            ]);
        }

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) throw new ValidationException($validator);

        if ($isStore) {
            $existing = DB::table('potential_customers')
                ->join('users', 'potential_customers.user_id', '=', 'users.id')
                ->where('phone', $data['phone'])
                ->where('country_code', $data['country_code'])
                ->select('users.name as user_name')
                ->first();

            if ($existing) {
                $v = Validator::make([], []);
                $v->errors()->add('phone', "هذا العميل مضاف مسبقاً بواسطة: {$existing->user_name}");
                throw new ValidationException($v);
            }
        }

        return $validator->validated();
    }

    public function getLatestUrgentByStatus($user, string $status, $limit = 5)
    {
        $query = PotentialCustomer::where('status', $status);
        if ($user->role !== UserRole::CEO) {
            $query->where('user_id', $user->id);
        }
        return $query->oldest('added_at')->take($limit)->get();
    }
}
