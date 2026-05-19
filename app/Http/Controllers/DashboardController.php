<?php

namespace App\Http\Controllers;

use App\Models\PotentialCustomer;
use App\Enums\PotentialCustomerStatus;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ==========================================
        // 1. لوحة تحكم المدير التنفيذي (CEO)
        // ==========================================
        if ($user->isCEO()) {

            // حساب أعداد الحالات الكلية
            $totalCustomers = PotentialCustomer::count();
            $newCount       = PotentialCustomer::where('status', PotentialCustomerStatus::NEW)->count(); // عملاء جدد (غير فعالين)
            $pendingCount   = PotentialCustomer::where('status', PotentialCustomerStatus::CONTACTED)->count();
            $confirmedCount = PotentialCustomer::where('status', PotentialCustomerStatus::CONFIRMED)->count();
            $cancelledCount = PotentialCustomer::where('status', PotentialCustomerStatus::CANCELLED)->count();

            // حساب النسب المئوية مع تفادي خطأ القسمة على صفر
            $safeTotal   = $totalCustomers > 0 ? $totalCustomers : 1;
            $opRatio     = round((($totalCustomers - $newCount) / $safeTotal) * 100, 1);
            $waitRatio   = round(($pendingCount / $safeTotal) * 100, 1);
            $closeRatio  = round(($confirmedCount / $safeTotal) * 100, 1);
            $rejectRatio = round(($cancelledCount / $safeTotal) * 100, 1);

            // حساب معدل النجاح (Win Rate) بناءً على الصفقات المحسومة فقط
            $decidedTotal = $confirmedCount + $cancelledCount;
            $winRate      = $decidedTotal > 0 ? round(($confirmedCount / $decidedTotal) * 100, 1) : 0;

            // إحصائيات مصادر العملاء
            $sourceStats = PotentialCustomer::select('source', DB::raw('count(*) as total'))
                ->groupBy('source')
                ->get()
                ->map(fn($item) => [
                    'label' => method_exists($item->source, 'label') ? $item->source->label() : ($item->source->value ?? $item->source),
                    'total' => $item->total
                ]);

            // أعلى 5 موظفين مبيعاً (الطلبات المؤكدة فقط)
            $topAgents = DB::table('potential_customers')
                ->join('users', 'potential_customers.added_by', '=', 'users.id')
                ->select('users.name', DB::raw('count(*) as total_sales'))
                ->where('potential_customers.status', PotentialCustomerStatus::CONFIRMED)
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('total_sales')
                ->take(5)
                ->get();

            return view('dashboards.ceo', compact(
                'totalCustomers',
                'newCount', // سيتم استخدامه في الكرت لعرض "العملاء غير الفعالين"
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
        $myCustomersQuery = PotentialCustomer::where('added_by', $user->id);

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