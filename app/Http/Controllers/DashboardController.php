<?php

namespace App\Http\Controllers;

use App\Models\PotentialCustomer;
use App\Enums\PotentialCustomerStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 🟢 فحص صلاحية الـ CEO
        if ($user->role === 'CEO' || $user->role === 'ceo') {
            
            // 1. جلب البيانات الشاملة للشركة بالكامل
            $totalCustomers = PotentialCustomer::count();
            $newCount       = PotentialCustomer::where('status', PotentialCustomerStatus::NEW)->count();
            $pendingCount   = PotentialCustomer::where('status', PotentialCustomerStatus::CONTACTED)->count();
            $confirmedCount = PotentialCustomer::where('status', PotentialCustomerStatus::CONFIRMED)->count();
            $cancelledCount = PotentialCustomer::where('status', PotentialCustomerStatus::CANCELLED)->count();

            // 2. الحسابات والنسب المئوية
            $safeTotal   = $totalCustomers > 0 ? $totalCustomers : 1;
            $opRatio     = round((($totalCustomers - $newCount) / $safeTotal) * 100, 1);
            $waitRatio   = round(($pendingCount / $safeTotal) * 100, 1);
            $closeRatio  = round(($confirmedCount / $safeTotal) * 100, 1);
            $rejectRatio = round(($cancelledCount / $safeTotal) * 100, 1);

            $decidedTotal = $confirmedCount + $cancelledCount;
            $winRate      = $decidedTotal > 0 ? round(($confirmedCount / $decidedTotal) * 100, 1) : 0;

            // تحليل مصادر الجلب للشركة
            $sourceStats = PotentialCustomer::select('source', DB::raw('count(*) as total'))
                ->groupBy('source')->get()->map(fn($item) => [
                    'label' => method_exists($item->source, 'label') ? $item->source->label() : $item->source->value,
                    'total' => $item->total
                ]);

            // قائمة أعلى 5 موظفين إغلاقاً للصفقات (تعديل الربط هنا أيضاً إلى added_by)
            $topAgents = DB::table('potential_customers')
                ->join('users', 'potential_customers.added_by', '=', 'users.id')
                ->select('users.name', DB::raw('count(*) as total_sales'))
                ->where('potential_customers.status', PotentialCustomerStatus::CONFIRMED)
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('total_sales')
                ->take(5)
                ->get();

            return view('dashboards.ceo', compact(
                'totalCustomers', 'newCount', 'pendingCount', 'confirmedCount', 
                'cancelledCount', 'opRatio', 'waitRatio', 'closeRatio', 'rejectRatio', 'winRate', 'topAgents'
            ))->with([
                'sourceLabels' => $sourceStats->pluck('label')->toArray(),
                'sourceData'   => $sourceStats->pluck('total')->toArray()
            ]);
        }

        // 🔵 حالة الـ Agent (تم تعديل البحث هنا ليعتمد على added_by المأخوذ من الموديل)
        $myCustomersQuery = PotentialCustomer::where('added_by', $user->id);

        $totalCustomers = (clone $myCustomersQuery)->count();
        $newCount       = (clone $myCustomersQuery)->where('status', PotentialCustomerStatus::NEW)->count();
        $pendingCount   = (clone $myCustomersQuery)->where('status', PotentialCustomerStatus::CONTACTED)->count();
        $confirmedCount = (clone $myCustomersQuery)->where('status', PotentialCustomerStatus::CONFIRMED)->count();
        $cancelledCount = (clone $myCustomersQuery)->where('status', PotentialCustomerStatus::CANCELLED)->count();

        // الحسابات والنسب المئوية الخاصة بالـ Agent نفسه
        $safeAgentTotal = $totalCustomers > 0 ? $totalCustomers : 1;
        $opRatio        = round((($totalCustomers - $newCount) / $safeAgentTotal) * 100, 1);
        $waitRatio      = round(($pendingCount / $safeAgentTotal) * 100, 1);
        $closeRatio     = round(($confirmedCount / $safeAgentTotal) * 100, 1);
        $rejectRatio    = round(($cancelledCount / $safeAgentTotal) * 100, 1);

        // جلب أحدث 5 عملاء يحتاجون تواصل فوري
        $recentUrgentCustomers = (clone $myCustomersQuery)
            ->whereIn('status', [PotentialCustomerStatus::NEW, PotentialCustomerStatus::CONTACTED])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboards.agent', compact(
            'totalCustomers', 'newCount', 'pendingCount', 'confirmedCount', 
            'cancelledCount', 'opRatio', 'waitRatio', 'closeRatio', 'rejectRatio', 'recentUrgentCustomers'
        ));
    }
}