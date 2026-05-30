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

            $usersWithCustomers = User::join('potential_customers', 'users.id', '=', 'potential_customers.user_id')
                ->select('users.id', 'users.name', DB::raw('count(potential_customers.id) as customers_count'))
                ->groupBy('users.id', 'users.name')
                ->get();

            $ceoQuery = PotentialCustomer::query();

            // تطبيق الفلاتر (الموظف، السنة، الشهر)
            if ($request->filled('user_id')) {
                $ceoQuery->where('user_id', $request->user_id);
            }

            if ($request->filled('year')) {
                $ceoQuery->whereYear('created_at', $request->year);
            }

            if ($request->filled('month')) {
                $ceoQuery->whereMonth('created_at', $request->month);
            }

            // حساب الأعداد
            $totalCustomers = (clone $ceoQuery)->count();
            $newCount       = (clone $ceoQuery)->where('status', PotentialCustomerStatus::NEW)->count();
            $pendingCount   = (clone $ceoQuery)->where('status', PotentialCustomerStatus::CONTACTED)->count();
            $confirmedCount = (clone $ceoQuery)->where('status', PotentialCustomerStatus::CONFIRMED)->count();
            $cancelledCount = (clone $ceoQuery)->where('status', PotentialCustomerStatus::CANCELLED)->count();

            // الحسابات النسبية
            $safeTotal   = $totalCustomers > 0 ? $totalCustomers : 1;
            $opRatio     = round((($totalCustomers - $newCount) / $safeTotal) * 100, 1);
            $waitRatio   = round(($pendingCount / $safeTotal) * 100, 1);
            $closeRatio  = round(($confirmedCount / $safeTotal) * 100, 1);
            $rejectRatio = round(($cancelledCount / $safeTotal) * 100, 1);

            $decidedTotal = $confirmedCount + $cancelledCount;
            $winRate      = $decidedTotal > 0 ? round(($confirmedCount / $decidedTotal) * 100, 1) : 0;

            // مصادر العملاء
            $sourceStats = (clone $ceoQuery)->select('source', DB::raw('count(*) as total'))
                ->groupBy('source')
                ->get()
                ->map(fn($item) => [
                    'label' => method_exists($item->source, 'label') ? $item->source->label() : ($item->source->value ?? $item->source),
                    'total' => $item->total
                ]);

            // أعلى 5 موظفين مبيعاً (مع تطبيق نفس الفلاتر)
            $topAgentsQuery = DB::table('potential_customers')
                ->join('users', 'potential_customers.user_id', '=', 'users.id')
                ->select('users.name', DB::raw('count(*) as total_sales'))
                ->where('potential_customers.status', PotentialCustomerStatus::CONFIRMED);

            if ($request->filled('year')) $topAgentsQuery->whereYear('potential_customers.created_at', $request->year);
            if ($request->filled('month')) $topAgentsQuery->whereMonth('potential_customers.created_at', $request->month);

            $topAgents = $topAgentsQuery->groupBy('users.id', 'users.name')
                ->orderByDesc('total_sales')
                ->take(5)
                ->get();

            return view('dashboards.ceo', compact(
                'usersWithCustomers', 'totalCustomers', 'newCount', 'pendingCount',
                'confirmedCount', 'cancelledCount', 'opRatio', 'waitRatio',
                'closeRatio', 'rejectRatio', 'winRate', 'topAgents'
            ))->with([
                'sourceLabels' => $sourceStats->pluck('label')->toArray(),
                'sourceData'   => $sourceStats->pluck('total')->toArray()
            ]);
        }

        // ==========================================
        // 2. لوحة تحكم المندوب (Agent)
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

        $recentUrgentCustomers = (clone $myCustomersQuery)
            ->whereIn('status', [PotentialCustomerStatus::NEW, PotentialCustomerStatus::CONTACTED])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboards.agent', compact(
            'totalCustomers', 'newCount', 'pendingCount', 'confirmedCount', 'cancelledCount',
            'opRatio', 'waitRatio', 'closeRatio', 'rejectRatio', 'recentUrgentCustomers'
        ))->with([
            'sourceLabels' => $sourceStats->pluck('label')->toArray(),
            'sourceData'   => $sourceStats->pluck('total')->toArray()
        ]);
    }
}