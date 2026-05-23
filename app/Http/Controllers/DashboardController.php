<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PotentialCustomer;
use App\Enums\PotentialCustomerStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // ==========================================
        // 1. لوحة تحكم المدير التنفيذي (CEO)
        // ==========================================
        if ($user->isCEO()) {

            // جلب المستخدمين الذين أضافوا عملاء محتملين فعلياً لتغذية الـ Dropdown (حل آمن بدون العلاقات)
            $usersWithCustomers = User::join('potential_customers', 'users.id', '=', 'potential_customers.user_id')
                ->select('users.id', 'users.name', DB::raw('count(potential_customers.id) as customers_count'))
                ->groupBy('users.id', 'users.name')
                ->get();

            // تجهيز الـ Query الأساسي للعملاء وقبول الفلترة بحسب المستخدم
            $ceoQuery = PotentialCustomer::query();

            if ($request->has('user_id') && $request->user_id != '') {
                $ceoQuery->where('user_id', $request->user_id);
            }

            // حساب أعداد الحالات الكلية (تتأثر بالفلتر تلقائياً)
            $totalCustomers = (clone $ceoQuery)->count();
            $newCount       = (clone $ceoQuery)->where('status', PotentialCustomerStatus::NEW)->count();
            $pendingCount   = (clone $ceoQuery)->where('status', PotentialCustomerStatus::CONTACTED)->count();
            $confirmedCount = (clone $ceoQuery)->where('status', PotentialCustomerStatus::CONFIRMED)->count();
            $cancelledCount = (clone $ceoQuery)->where('status', PotentialCustomerStatus::CANCELLED)->count();

            // حساب النسب المئوية مع تفادي خطأ القسمة على صفر
            $safeTotal   = $totalCustomers > 0 ? $totalCustomers : 1;
            $opRatio     = round((($totalCustomers - $newCount) / $safeTotal) * 100, 1);
            $waitRatio   = round(($pendingCount / $safeTotal) * 100, 1);
            $closeRatio  = round(($confirmedCount / $safeTotal) * 100, 1);
            $rejectRatio = round(($cancelledCount / $safeTotal) * 100, 1);

            // حساب معدل النجاح (Win Rate) بناءً على الصفقات المحسومة فقط
            $decidedTotal = $confirmedCount + $cancelledCount;
            $winRate      = $decidedTotal > 0 ? round(($confirmedCount / $decidedTotal) * 100, 1) : 0;

            // إحصائيات مصادر العملاء (تتأثر بالفلتر أيضاً)
            $sourceStats = (clone $ceoQuery)->select('source', DB::raw('count(*) as total'))
                ->groupBy('source')
                ->get()
                ->map(fn($item) => [
                    'label' => method_exists($item->source, 'label') ? $item->source->label() : ($item->source->value ?? $item->source),
                    'total' => $item->total
                ]);

            // أعلى 5 موظفين مبيعاً (ثابت لعرض الأداء العام للموظفين في الشركة دائماً)
            $topAgents = DB::table('potential_customers')
                ->join('users', 'potential_customers.user_id', '=', 'users.id')
                ->select('users.name', DB::raw('count(*) as total_sales'))
                ->where('potential_customers.status', PotentialCustomerStatus::CONFIRMED)
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('total_sales')
                ->take(5)
                ->get();

            return view('dashboards.ceo', compact(
                'usersWithCustomers',
                'totalCustomers',
                'newCount', 
                'pendingCount',
                'confirmedCount',
                'cancelledCount',
                'opRatio',
                'waitRatio',
                'closeRatio',
                'rejectRatio',
                'winRate',
                'topAgents'
            ))->with([
                'sourceLabels' => $sourceStats->pluck('label')->toArray(),
                'sourceData'   => $sourceStats->pluck('total')->toArray()
            ]);
        }

        // ==========================================
        // 2. لوحة تحكم المندوب / العميل العادي (Agent)
        // ==========================================
        $myCustomersQuery = PotentialCustomer::where('user_id', $user->id);

        $totalCustomers = (clone $myCustomersQuery)->count();
        $newCount       = (clone $myCustomersQuery)->where('status', PotentialCustomerStatus::NEW)->count();
        $pendingCount   = (clone $myCustomersQuery)->where('status', PotentialCustomerStatus::CONTACTED)->count();
        $confirmedCount = (clone $myCustomersQuery)->where('status', PotentialCustomerStatus::CONFIRMED)->count();
        $cancelledCount = (clone $myCustomersQuery)->where('status', PotentialCustomerStatus::CANCELLED)->count();

        $safeAgentTotal = $totalCustomers > 0 ? $totalCustomers : 1;
        $opRatio        = round((($totalCustomers - $newCount) / $safeAgentTotal) * 100, 1);
        $waitRatio      = round(($pendingCount / $safeAgentTotal) * 100, 1);
        $closeRatio     = round(($confirmedCount / $safeAgentTotal) * 100, 1);
        $rejectRatio    = round(($cancelledCount / $safeAgentTotal) * 100, 1);

        $sourceStats = (clone $myCustomersQuery)
            ->select('source', DB::raw('count(*) as total'))
            ->groupBy('source')
            ->get()
            ->map(fn($item) => [
                'label' => method_exists($item->source, 'label') ? $item->source->label() : ($item->source->value ?? $item->source),
                'total' => $item->total
            ]);

        // جلب أحدث العملاء العاجلين للمندوب للمتابعة السريعة
        $recentUrgentCustomers = (clone $myCustomersQuery)
            ->whereIn('status', [PotentialCustomerStatus::NEW, PotentialCustomerStatus::CONTACTED])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboards.agent', compact(
            'totalCustomers',
            'newCount',
            'pendingCount',
            'confirmedCount',
            'cancelledCount',
            'opRatio',
            'waitRatio',
            'closeRatio',
            'rejectRatio',
            'recentUrgentCustomers'
        ))->with([
            'sourceLabels' => $sourceStats->pluck('label')->toArray(),
            'sourceData'   => $sourceStats->pluck('total')->toArray()
        ]);
    }
}