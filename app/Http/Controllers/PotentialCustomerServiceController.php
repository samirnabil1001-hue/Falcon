<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PotentialCustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PotentialCustomerServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. جلب أحدث معرف خدمة (ID) لكل عميل فريد بأعلى أداء ومتوافق مع Strict Mode
        $latestServiceIds = PotentialCustomerService::select(DB::raw('MAX(id) as id'))
            ->groupBy('potential_customer_id')
            ->pluck('id');

        // 2. بناء الاستعلام بناءً على الخدمات الفريدة فقط باستخدام السلسلة المرنة when()
        $query = PotentialCustomerService::whereIn('id', $latestServiceIds)
            ->with([
                'potentialCustomer',
                'user'
            ]);

        // تطبيق البحث الذكي
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($subQuery) use ($search) {
                $subQuery->where('notes', 'like', "%{$search}%")
                    ->orWhere('service_type', 'like', "%{$search}%")
                    ->orWhereHas('potentialCustomer', function ($customer) use ($search) {
                        $customer->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    });
            });
        });

        // الفلترة حسب نوع الخدمة
        $query->when($request->filled('service_type'), function ($q) use ($request) {
            $q->where('service_type', $request->service_type);
        });

        // تصفية السجلات حسب المستخدم الحالي أو الموظف المختار
        $userId = $request->boolean('only_me') ? auth()->id() : $request->user_id;
        $query->when($userId, function ($q, $userId) {
            $q->where('user_id', $userId);
        });

        // الفلترة بالتاريخ (من وإلى)
        $query->when($request->filled('date_from'), function ($q) use ($request) {
            $q->whereDate('created_at', '>=', $request->date_from);
        })->when($request->filled('date_to'), function ($q) use ($request) {
            $q->whereDate('created_at', '<=', $request->date_to);
        });

        // إعدادات الفرز الآمن
        $allowedSorts = ['created_at', 'service_type', 'id'];
        $sortBy = in_array($request->sort_by, $allowedSorts) ? $request->sort_by : 'created_at';
        $sortOrder = strtolower($request->sort_order ?? 'desc') === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortBy, $sortOrder);

        // جلب البيانات مع الحفاظ على الـ Query String بالمتصفح للـ Pagination
        $services = $query->paginate(10)->withQueryString();

        // جلب الموظفين الذين لديهم خدمات مسجلة بالفعل مع استبعاد المستخدم الحالي (أنت)
        $users = User::join('potential_customer_services', 'users.id', '=', 'potential_customer_services.user_id')
            ->select('users.id', 'users.name', DB::raw('count(potential_customer_services.id) as customers_count'))
            ->where('users.id', '!=', auth()->id())
            ->groupBy('users.id', 'users.name')
            ->orderBy('users.name')
            ->get();

        return view('potential-customer-services.index', compact('services', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('potential-customer-services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'potential_customer_id' => 'required|exists:potential_customers,id',
            'user_id' => 'required|exists:users,id',
            'service_type' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        PotentialCustomerService::create($validated);

        return redirect()->route('potential-customer-services.index')
            ->with('success', 'تم إضافة الخدمة للعميل بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PotentialCustomerService $service)
    {
        // تم استخدام الـ Route Model Binding المباشر والأسرع بدلاً من جلب الـ ID يدوياً
        $service->load(['potentialCustomer', 'user']);

        return view('potential-customer-services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PotentialCustomerService $service)
    {
        return view('potential-customer-services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PotentialCustomerService $service)
    {
        $validated = $request->validate([
            'potential_customer_id' => 'sometimes|exists:potential_customers,id',
            'user_id' => 'sometimes|exists:users,id',
            'service_type' => 'sometimes|string',
            'notes' => 'nullable|string',
        ]);

        $service->update($validated);

        return redirect()->route('potential-customer-services.index')
            ->with('success', 'تم تحديث بيانات الخدمة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PotentialCustomerService $service)
    {
        $service->delete();

        return redirect()->route('potential-customer-services.index')
            ->with('success', 'تم حذف الخدمة بنجاح.');
    }
}