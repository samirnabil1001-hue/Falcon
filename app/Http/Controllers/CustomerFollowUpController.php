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

    public function store(Request $request, $customerId)
    {
        $validated = $request->validate([
            'status'              => 'required|string',
            'reason'              => 'nullable|string',
            'next_follow_up_date' => 'nullable|date',
            'notes'               => 'nullable|string',
        ]);

        try {
            // استدعاء الـ Service لمعالجة البيانات
            $this->followUpService->logFollowUp($customerId, $validated);

            return redirect()->back()->with('success', 'تم تحديث حالة العميل وتسجيل المتابعة بنجاح.');
        } catch (\Exception $e) {
            // التعامل مع أي خطأ غير متوقع وإرجاع رسالة خطأ آمنة
            return redirect()->back()->withErrors(['error' => 'عذراً، حدث خطأ أثناء تحديث البيانات.']);
        }
    }
}