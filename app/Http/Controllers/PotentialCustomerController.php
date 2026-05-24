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

        // جلب المستخدمين فقط إذا كان المسجل CEO لعرضهم في الـ Dropdown
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
        // يفضل دائماً إضافة التحقق هنا أو استخدام Form Request
        $this->customerService->store($request->all(), auth()->id());

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

        // 1. التحقق من البيانات أثناء التعديل العادي
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'source' => 'required|string|max:100',
        ]);

        // 2. تمرير الموديل والبيانات المعدلة للسيرفيس
        $this->customerService->update($potentialCustomer, $validated);

        return redirect()
            ->route('potential-customers.index')
            ->with('success', 'تم تحديث بيانات العميل بنجاح.');
    }

    /**
     * تحديث حالة العميل وتسجيل المتابعة التاريخية (Web Version)
     */
    /**
     * تحديث حالة العميل وتسجيل المتابعة التاريخية (Web Version)
     */
    public function updateStatus(Request $request, PotentialCustomer $potentialCustomer)
    {
        $this->authorize('updateStatus', $potentialCustomer);

        //  تحديث الـ Validation لكي يسمح بحقول الخدمة بالمرور عند التأكيد
        $validated = $request->validate([
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'reason' => 'nullable|string',
            'next_follow_up_date' => 'nullable|date',

            // 👇 الحقول المطلوبة التي كانت تسبب الخطأ في السيرفيس
            'service_type' => 'required_if:status,confirmed|string|nullable', // ستصبح مطلوبة فقط إذا كانت الحالة confirmed
            'service_notes' => 'nullable|string',
        ]);

        // تنفيذ التحديث واللوج من خلال السيرفيس
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
        // 1. التحقق من صلاحية الـ CEO عبر الـ Policy
        $this->authorize('updateAddedBy', $potentialCustomer);

        // 2. التحقق من صحة المستخدم الجديد
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // 3. تحديث الحقل مباشرة في قاعدة البيانات
        $potentialCustomer->update([
            'user_id' => $validated['user_id']
        ]);

        return back()->with('success', 'تم تعديل الموظف المسؤول عن العميل بنجاح.');
    }
}