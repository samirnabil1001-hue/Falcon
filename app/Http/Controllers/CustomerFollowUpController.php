<?php

namespace App\Http\Controllers;

use App\Services\CustomerFollowUpService;
use App\Models\PotentialCustomer;
use App\Enums\PotentialCustomerStatus;
use App\Enums\CompanyService;
use App\Models\User;
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
        $users = User::where('is_active', true)->get();

        // جلب الأعداد الحقيقية من السيرفس
        $actualCounts = $this->followUpService->logCustomerFollowUpsCount($request);

        // بناء الداتا بالشكل المطلوب للـ View مع وضع 0 كقيمة افتراضية
        $statusCounts = collect(PotentialCustomerStatus::cases())->map(function ($status) use ($actualCounts) {
            return [
                'status' => $status->value,                     // 'new', 'contacted'...
                'label' => $status->label(),                   // 'جديد', 'قيد المتابعة'...
                'count' => $actualCounts[$status->value] ?? 0, // العدد من الداتابيز أو 0 لو مش موجود
            ];
        });

        return view('potential_customers.follow-ups-index', compact('customers', 'users', 'statusCounts'));
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
        // جلب البيانات مع الموظفين المرتبطين بها مباشرة
        $customer = PotentialCustomer::with([
            'followUps.user',
            'services.user'
        ])->findOrFail($customerId);

        $unifiedTimeline = collect();

        // 1. تحويل المتابعات
        foreach ($customer->followUps as $log) {
            if ($log->status->value === 'confirmed')
                continue;

            $unifiedTimeline->push([
                'type' => 'follow_up',
                'status' => $log->status->value,
                'status_label' => $log->status->label(),
                'reason' => is_object($log->reason) ? $log->reason->label() : ($log->reason ?? 'بدون سبب'),
                'notes' => $log->notes,
                'created_at' => $log->created_at,
                'next_follow_up_at' => $log->next_follow_up_at,
                // الوصول للاسم من الموديل مباشرة
                'user_name' => $log->user->name ?? 'System',
            ]);
        }

        // 2. تحويل الخدمات
        foreach ($customer->services as $service) {
            $unifiedTimeline->push([
                'type' => 'service',
                'status' => 'confirmed',
                'status_label' => 'تنفيذ',
                'reason' => is_object($service->service_type) ? $service->service_type->label() : $service->service_type,
                'notes' => $service->notes,
                'created_at' => $service->created_at,
                'next_follow_up_at' => null,
                // الوصول للاسم من الموديل مباشرة
                'user_name' => $service->user->name ?? 'System',
            ]);
        }

        $sortedTimeline = $unifiedTimeline->sortByDesc('created_at');

        return view('potential_customers.show-history', compact('customer', 'sortedTimeline'));
    }
}