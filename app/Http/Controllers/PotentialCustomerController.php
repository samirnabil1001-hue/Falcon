<?php

namespace App\Http\Controllers;

use App\Models\PotentialCustomer;
use App\Services\PotentialCustomerService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Enums\PotentialCustomerStatus;
use Illuminate\Validation\Rule;

class PotentialCustomerController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected PotentialCustomerService $customerService
    ) {
    }

    public function index(Request $request)
    {
        $customers = $this->customerService->getPaginated(auth()->user(), $request->all());

        // جلب المستخدمين فقط إذا كان المسجل CEO لعرضهم في الـ Dropdown
        $users = auth()->user()->isCEO()
            ? \App\Models\User::all()
            : collect();

        return view('potential_customers.index', compact('customers', 'users'));
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

        // 1. التحقق من البيانات أثناء التعديل العادي
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'source' => 'required|string|max:100',
        ]);

        // 2. تمرير الموديل والبيانات المعدلة
        $this->customerService->update($potentialCustomer, $validated);

        return redirect()
            ->route('potential-customers.index')
            ->with('success', 'تم تحديث بيانات العميل بنجاح.');
    }

    public function updateStatus(Request $request, PotentialCustomer $potentialCustomer)
    {
        $this->authorize('updateStatus', $potentialCustomer);

        $this->customerService->updateStatusAndLogFollowUp(
            $potentialCustomer,
            $request->all(),
            auth()->id()
        );

        return back()->with('success', 'تم تحديث حالة العميل وتسجيل المتابعة بنجاح.');
    }

    public function destroy(PotentialCustomer $potentialCustomer)
    {
        $this->authorize('delete', $potentialCustomer);

        $this->customerService->delete($potentialCustomer);

        return redirect()
            ->route('potential-customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * تحديث الموظف المسؤول عن العميل (خاص بالـ CEO فقط)
     */
    public function updateAddedBy(Request $request, PotentialCustomer $potentialCustomer)
    {
        // 1. التحقق من صلاحية الـ CEO
        $this->authorize('updateAddedBy', $potentialCustomer);

        // 2. التحقق من صحة المستخدم الجديد
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // 3. تحديث الحقل مباشرة
        $potentialCustomer->update([
            'user_id' => $validated['user_id']
        ]);

        return back()->with('success', 'تم تعديل المسؤول عن العميل بنجاح.');
    }
}