<?php

namespace App\Http\Controllers;

use App\Services\CustomerFollowUpService;
use App\Models\PotentialCustomer;
use Illuminate\Http\Request;

class CustomerFollowUpController extends Controller
{
    protected CustomerFollowUpService $followUpService;

    public function __construct(CustomerFollowUpService $followUpService)
    {
        $this->followUpService = $followUpService;
    }

    /**
     * عرض الصفحة الرئيسية للمتابعات مع دعم الفلترة والبحث والفرز
     */
    public function index(Request $request)
    {
        $customers = $this->followUpService->getPaginatedCustomers($request, 10);
        
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
    
    /**
     * عرض سجل متابعات العميل
     */
    public function show($customerId)
    {
        $customer = PotentialCustomer::with([
            'followUps' => function ($query) {
                $query->latest();
            }
        ])->findOrFail($customerId);

        return view('potential_customers.show-history', compact('customer'));
    }
}