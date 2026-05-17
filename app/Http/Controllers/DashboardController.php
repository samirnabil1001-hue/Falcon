<?php

namespace App\Http\Controllers;

use App\Models\PotentialCustomer;
use App\Enums\PotentialCustomerStatus;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * عرض لوحة التحكم مع الإحصائيات الحقيقية.
     */
    public function index()
    {
        // 1. جلب أعداد العملاء حسب الحالات من قاعدة البيانات
        $totalCustomers = PotentialCustomer::count();
        $pendingCount   = PotentialCustomer::where('status', PotentialCustomerStatus::CONTACTED)->count();
        $confirmedCount = PotentialCustomer::where('status', PotentialCustomerStatus::CONFIRMED)->count();
        $cancelledCount = PotentialCustomer::where('status', PotentialCustomerStatus::CANCELLED)->count();
        
        // تفادي خطأ القسمة على صفر إذا كانت قاعدة البيانات فارغة
        $safeTotal = $totalCustomers > 0 ? $totalCustomers : 1;

        // 2. حساب النسب المئوية المطلوبة للـ Charts والـ Progress Bars
        $opRatio      = round((($confirmedCount + $pendingCount) / $safeTotal) * 100, 1);
        $waitRatio    = round(($pendingCount / $safeTotal) * 100, 1);
        $closeRatio   = round(($confirmedCount / $safeTotal) * 100, 1);
        $rejectRatio  = round(($cancelledCount / $safeTotal) * 100, 1);

        // 3. تمرير البيانات إلى صفحة الـ Blade
        return view('dashboard', compact(
            'totalCustomers',
            'pendingCount',
            'confirmedCount',
            'cancelledCount',
            'opRatio',
            'waitRatio',
            'closeRatio',
            'rejectRatio'
        ));
    }
}