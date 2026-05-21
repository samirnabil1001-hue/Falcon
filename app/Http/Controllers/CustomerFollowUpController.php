<?php

namespace App\Http\Controllers;

use App\Services\CustomerFollowUpService;
use App\Models\PotentialCustomer;
use App\Enums\PotentialCustomerStatus;
use App\Enums\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

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
        // 1. تحديد قيم التأكيد للمقارنة (سواء كانت Enum أو String عادي)
        $confirmedValue = defined('\App\Enums\PotentialCustomerStatus::CONFIRMED') 
            ? PotentialCustomerStatus::CONFIRMED->value 
            : 'confirmed';

        // 2. التحقق من البيانات مع إضافة شروط خاصة بحالة التثبيت (confirmed)
        $validated = $request->validate([
            'status' => 'required|string',
            'reason' => 'nullable|string',
            'next_follow_up_date' => 'nullable|date',
            'notes' => 'nullable|string',
            
            // إذا كانت الحالة confirmed، يصبح نوع الخدمة مطلوباً إجبارياً
            'service_type' => [
                $request->status === $confirmedValue ? 'required' : 'nullable',
                new Enum(CompanyService::class)
            ],
            'service_notes' => 'nullable|string',
        ]);

        try {
            // 3. تمرير البيانات المفلترة إلى الـ Service
            $this->followUpService->logFollowUp((int) $customerId, $validated);

            return redirect()->back()->with('success', 'تم تحديث حالة العميل وتسجيل المتابعة بنجاح.');
        } catch (\Exception $e) {
            // يمكنك استخدام $e->getMessage() هنا أثناء التطوير لمعرفة تفاصيل أي خطأ فوري
            return redirect()->back()->withErrors(['error' => 'عذراً، حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage()]);
        }
    }
    
    /**
     * عرض سجل متابعات العميل
     */
    public function show($customerId)
    {
        // قمنا بشحن علاقة الـ services أيضاً لنتمكن من عرض الخدمات المربوطة بالعميل في صفحة السجل
        $customer = PotentialCustomer::with([
            'followUps' => function ($query) {
                $query->latest();
            },
            'services' => function ($query) {
                $query->latest();
            }
        ])->findOrFail($customerId);

        return view('potential_customers.show-history', compact('customer'));
    }
}