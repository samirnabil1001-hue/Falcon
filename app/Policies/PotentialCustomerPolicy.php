<?php

namespace App\Http\Controllers;

use App\Models\PotentialCustomer;
use App\Services\PotentialCustomerService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // ضروري لاستخدام authorize

class PotentialCustomerController extends Controller
{
    use AuthorizesRequests;

    /**
     * حقن الخدمة عبر الـ Constructor
     */
    public function __construct(
        protected PotentialCustomerService $customerService
    ) {}

    /**
     * عرض القائمة (تتم الفلترة داخل السيرفس)
     */
    public function index()
    {
        $customers = $this->customerService->getPaginatedCustomers(auth()->user());

        return view('potential_customers.index', compact('customers'));
    }

    /**
     * حفظ عميل جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'required|string|max:20',
            'source' => 'nullable|string|max:100',
            'status' => 'required|string',
        ]);

        $this->customerService->createCustomer($validated, auth()->id());

        return redirect()->route('potential-customers.index')
            ->with('success', 'Customer added successfully!');
    }

    /**
     * صفحة التعديل (محمية بالبوليسي)
     */
    public function edit(PotentialCustomer $potentialCustomer)
    {
        // استخدام دالة update المعرفة في PotentialCustomerPolicy
        $this->authorize('update', $potentialCustomer);

        return view('potential_customers.edit', compact('potentialCustomer'));
    }

    /**
     * تحديث البيانات (محمية بالبوليسي)
     */
    public function update(Request $request, PotentialCustomer $potentialCustomer)
    {
        $this->authorize('update', $potentialCustomer);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'required|string|max:20',
            'source' => 'nullable|string|max:100',
            'status' => 'required|string',
        ]);

        $potentialCustomer->update($validated);

        return redirect()->route('potential-customers.index')
            ->with('success', 'Customer updated successfully!');
    }

    /**
     * حذف العميل (محمية بالبوليسي)
     */
    public function destroy(PotentialCustomer $potentialCustomer)
    {
        $this->authorize('delete', $potentialCustomer);

        $potentialCustomer->delete();

        return redirect()->route('potential-customers.index')
            ->with('success', 'Customer deleted successfully!');
    }
}