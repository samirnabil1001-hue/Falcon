<?php

namespace App\Http\Controllers;

use App\Services\CustomerFollowUpService;
use Illuminate\Http\Request;

class CustomerFollowUpController extends Controller
{
    protected CustomerFollowUpService $followUpService;

    public function __construct(CustomerFollowUpService $followUpService)
    {
        $this->followUpService = $followUpService;
    }

    /**
     * عرض الصفحة الرئيسية للمتابعات
     */
    public function index()
    {
        // تم نقل الـ Logic بالكامل إلى الـ Service هنا 👇
        $customers = $this->followUpService->getPaginatedCustomers(10);

        return view('potential_customers.follow-ups-index', compact('customers'));
    }

    /**
     * حفظ متابعة وتحديث الحالة
     */
    public function store(Request $request, $customerId)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'reason' => 'nullable|string',
            'next_follow_up_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        try {
            $this->followUpService->logFollowUp((int) $customerId, $validated);

            return redirect()->back()->with('success', 'تم تحديث حالة العميل وتسجيل المتابعة بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'عذراً، حدث خطأ أثناء تحديث البيانات.']);
        }
    }
    public function show($customerId)
    {
        // جلب العميل مع المتابعات الخاصة به مرتبة من الأحدث للأقدم
        $customer = \App\Models\PotentialCustomer::with([
            'followUps' => function ($query) {
                $query->latest();
            }
        ])->findOrFail($customerId);

        // 👈 التعديل هنا: المسار المضمون والمشترك في مشروعك حالياً
        return view('potential_customers.show-history', compact('customer'));
    }
}