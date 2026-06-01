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

    /**
     * عرض قائمة العملاء المحتملين مع الفلترة
     */
    public function index(Request $request)
    {
        $customers = $this->customerService->getPaginated(auth()->user(), $request->all());

        $users = auth()->user()->isCEO()
            ? \App\Models\User::all()
        : collect();

        return view('potential_customers.index', compact('customers', 'users'));
    }

    /**
     * عرض صفحة إنشاء عميل محتمل جديد
     */
    public function create()
    {
        return view('potential_customers.create');
    }

    /**
     * حفظ عميل محتمل جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        // 1. التحقق من البيانات القادمة من الـ Form
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:50',
            'country_code' => 'required|string|max:10', // إجباري لضمان وصوله دائماً
            'source'       => 'required|string|max:100',
        ]);

        // تأمين إضافي في حال وصوله فارغاً كـ String
        if (empty($validated['country_code'])) {
            $validated['country_code'] = '+20';
        }

        // 2. تمرير المصفوفة التي تحتوي على كود الدولة للسيرفيس
        $this->customerService->store($validated, auth()->id());

        return redirect()
            ->route('potential-customers.index')
            ->with('success', 'تم إنشاء العميل بنجاح.');
    }

    /**
     * عرض تفاصيل عميل محتمل محدد
     */
    public function show(PotentialCustomer $potentialCustomer)
    {
        $this->authorize('view', $potentialCustomer);

        return view('potential_customers.show', compact('potentialCustomer'));
    }

    /**
     * عرض صفحة تعديل بيانات العميل
     */
    public function edit(PotentialCustomer $potentialCustomer)
    {
        $this->authorize('update', $potentialCustomer);

        return view('potential_customers.edit', compact('potentialCustomer'));
    }

    /**
     * تحديث بيانات العميل الأساسية
     */
    public function update(Request $request, PotentialCustomer $potentialCustomer)
    {
        $this->authorize('update', $potentialCustomer);

        // 1. التحقق من البيانات أثناء التعديل
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:50',
            'country_code' => 'required|string|max:10', // إجباري عند التعديل أيضاً
            'source'       => 'required|string|max:100',
        ]);

        if (empty($validated['country_code'])) {
            $validated['country_code'] = '+20';
        }

        // 2. تمرير المصفوفة المُحدثة كاملة بكود الدولة إلى السيرفيس
        $this->customerService->update($potentialCustomer, $validated);

        return redirect()
            ->route('potential-customers.index')
            ->with('success', 'تم تحديث بيانات العميل بنجاح.');
    }

    /**
     * تحديث حالة العميل وتسجيل المتابعة التاريخية (Web Version)
     */
    public function updateStatus(Request $request, PotentialCustomer $potentialCustomer)
    {
        $this->authorize('updateStatus', $potentialCustomer);

        $confirmedValue = PotentialCustomerStatus::CONFIRMED->value;

        $validated = $request->validate([
            'status'              => 'required|string',
            'notes'               => 'nullable|string',
            'reason'              => 'nullable|string',
            'next_follow_up_date' => 'nullable|date',
            'service_type'        => "required_if:status,{$confirmedValue},confirmed,CONFIRMED|string|nullable", 
            'service_notes'       => 'nullable|string',
        ]);

        $this->customerService->updateStatusAndLogFollowUp(
            $potentialCustomer,
            $validated,
            auth()->id()
        );

        return back()->with('success', 'تم تحديث حالة العميل وتسجيل المتابعة بنجاح.');
    }

    /**
     * حذف عميل محتمل
     */
    public function destroy(PotentialCustomer $potentialCustomer)
    {
        $this->authorize('delete', $potentialCustomer);

        $this->customerService->delete($potentialCustomer);

        return redirect()
            ->route('potential-customers.index')
            ->with('success', 'تم حذف العميل بنجاح.');
    }

    /**
     * تحديث الموظف المسؤول عن العميل (خاص بالـ CEO فقط)
     */
    public function updateAddedBy(Request $request, PotentialCustomer $potentialCustomer)
    {
        $this->authorize('updateAddedBy', $potentialCustomer);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $potentialCustomer->update([
            'user_id' => $validated['user_id']
        ]);

        return back()->with('success', 'تم تعديل الموظف المسؤول عن العميل بنجاح.');
    }
}