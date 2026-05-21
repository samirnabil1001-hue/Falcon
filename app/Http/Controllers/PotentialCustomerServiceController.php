<?php

namespace App\Http\Controllers;

use App\Models\PotentialCustomerService;
use Illuminate\Http\Request;

class PotentialCustomerServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PotentialCustomerService::with([
            'potentialCustomer',
            'user'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('notes', 'like', "%{$search}%")

                    ->orWhere('service_type', 'like', "%{$search}%")

                    ->orWhereHas('potentialCustomer', function ($customer) use ($search) {
                        $customer->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })

                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by service type
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        // Filter by employee
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Date from
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Date to
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'created_at',
            'service_type',
            'id'
        ];

        $sortBy = in_array($request->sort_by, $allowedSorts)
            ? $request->sort_by
            : 'created_at';

        $sortOrder = $request->sort_order === 'asc'
            ? 'asc'
            : 'desc';

        $query->orderBy($sortBy, $sortOrder);

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $services = $query->paginate(10)
            ->appends($request->query());

        return view(
            'potential-customer-services.index',
            compact('services')
        );
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'potential_customer_id' => 'required|exists:potential_customers,id',
            'user_id' => 'required|exists:users,id',
            'service_type' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $service = PotentialCustomerService::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Potential customer service created successfully',
            'data' => $service
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $service = PotentialCustomerService::with([
            'potentialCustomer',
            'user'
        ])->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $service
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = PotentialCustomerService::findOrFail($id);

        $validated = $request->validate([
            'potential_customer_id' => 'sometimes|exists:potential_customers,id',
            'user_id' => 'sometimes|exists:users,id',
            'service_type' => 'sometimes|string',
            'notes' => 'nullable|string',
        ]);

        $service->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Potential customer service updated successfully',
            'data' => $service
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = PotentialCustomerService::findOrFail($id);

        $service->delete();

        return response()->json([
            'status' => true,
            'message' => 'Potential customer service deleted successfully'
        ]);
    }
}