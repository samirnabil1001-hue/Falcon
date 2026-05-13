<?php

namespace App\Http\Controllers;

use App\Models\PotentialCustomer;
use App\Services\PotentialCustomerService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PotentialCustomerController extends Controller
{
    use AuthorizesRequests;

   
    public function __construct(
        protected PotentialCustomerService $customerService
    ) {}

 
    public function index()
    {
        $customers = $this->customerService->list(auth()->user());

        return view('potential_customers.index', compact('customers'));
    }


    public function create()
    {
        return view('potential_customers.create');
    }

    public function store(Request $request)
    {
        $this->customerService->store($request->all(), auth()->id());

        return redirect()
            ->route('potential-customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(PotentialCustomer $potentialCustomer)
    {
        $this->authorize('view', $potentialCustomer);

        return view('potential_customers.show', compact('potentialCustomer'));
    }

    
    public function edit(PotentialCustomer $potentialCustomer)
    {
        $this->authorize('update', $potentialCustomer);

        return view('potential_customers.edit', compact('potentialCustomer'));
    }

  
    public function update(Request $request, PotentialCustomer $potentialCustomer)
    {
        $this->authorize('update', $potentialCustomer);

        $this->customerService->update($potentialCustomer, $request->all());

        return redirect()
            ->route('potential-customers.index')
            ->with('success', 'Customer updated successfully.');
    }

 
    public function destroy(PotentialCustomer $potentialCustomer)
    {
        $this->authorize('delete', $potentialCustomer);

        $this->customerService->delete($potentialCustomer);

        return redirect()
            ->route('potential-customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}