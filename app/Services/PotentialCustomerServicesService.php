<?php

namespace App\Services;

// استيراد الموديلات من مسارها الصحيح (App\Models)
use App\Models\User;
use App\Models\PotentialCustomer;
use App\Models\CustomerFollowUp;
use App\Models\PotentialCustomerService;

// استيراد الـ Enums والـ الفئات المساعدة في لارافيل
use App\Enums\PotentialCustomerSource;
use App\Enums\PotentialCustomerStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Enum;

class PotentialCustomerServicesService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * جلب الخدمات المفلترة والموظفين المرتبطين بها لصفحة الـ Index والـ Dashboard.
     */
    public function getFilteredServicesData(array $filters, int $currentUserId): array
    {
        // 1. جلب أحدث معرف خدمة (ID) لكل عميل فريد (تجنب التكرار)
        $latestServiceIds = PotentialCustomerService::select(DB::raw('MAX(id) as id'))
            ->groupBy('potential_customer_id')
            ->pluck('id');

        // 2. بناء الاستعلام الأساسي مع العلاقات المطلوبة لتقليل الـ Queries (Eager Loading)
        $query = PotentialCustomerService::whereIn('id', $latestServiceIds)
            ->with(['potentialCustomer', 'user']);

        // 3. تطبيق البحث الذكي بالاسم، التليفون، الملاحظات، أو نوع الخدمة
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($subQuery) use ($search) {
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
        }

        // 4. الفلترة حسب نوع الخدمة المحددة
        if (!empty($filters['service_type'])) {
            $query->where('service_type', $filters['service_type']);
        }

        // 5. تصفية السجلات حسب المستخدم الحالي (أنا فقط) أو موظف معين تم اختياره
        $userId = filter_var($filters['only_me'] ?? false, FILTER_VALIDATE_BOOLEAN) 
            ? $currentUserId 
            : ($filters['user_id'] ?? null);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        // 6. الفلترة بمدى التاريخ (من وإلى)
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // 7. إعدادات الفرز والترتيب الآمن
        $allowedSorts = ['created_at', 'service_type', 'id'];
        $sortBy = in_array($filters['sort_by'] ?? null, $allowedSorts) ? $filters['sort_by'] : 'created_at';
        $sortOrder = strtolower($filters['sort_order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // 8. جلب البيانات مقسمة لصفحات (Pagination)
        $services = $query->paginate(10)->withQueryString();

        // 9. جلب قائمة الموظفين الذين يمتلكون خدمات مسجلة بالفعل مع استبعاد المستخدم الحالي
        $users = User::join('potential_customer_services', 'users.id', '=', 'potential_customer_services.user_id')
            ->select('users.id', 'users.name', DB::raw('count(potential_customer_services.id) as customers_count'))
            ->where('users.id', '!=', $currentUserId)
            ->groupBy('users.id', 'users.name')
            ->orderBy('users.name')
            ->get();

        return compact('services', 'users');
    }

    /**
     * تأكيد حالة العميل وتغييرها لـ CONFIRMED، وإنشاء الخدمة والـ Log المرتبط بها.
     */
    public function updateStatusAndLogFollowUp(PotentialCustomer $customer, array $data, int $userId): PotentialCustomer
    {
        // تشغيل الـ Validation المخصص
        $validated = $this->validateData($data, false);

        return DB::transaction(function () use ($customer, $validated, $userId) {

            // 1. تحديث حالة العميل إلى مؤكد (أو الحالة القادمة من الريكويست)
            $customer->update([
                'status' => $validated['status']
            ]);

            // 2. تسجيل الـ Follow Up Log في قاعدة البيانات لتوثيق الحركة
            CustomerFollowUp::create([
                'potential_customer_id' => $customer->id,
                'user_id'               => $userId,
                'status'                => $validated['status'],
                'reason'                => $validated['reason'] ?? null,
                'next_follow_up_at'     => $validated['next_follow_up_date'] ?? null,
                'notes'                 => $validated['notes'] ?? null,
            ]);

            // 3. إنشاء السجل الفعلي للخدمة داخل جدول الخدمات
            PotentialCustomerService::create([
                'potential_customer_id' => $customer->id,
                'user_id'               => $userId,
                'service_type'          => $validated['service_type'],
                'notes'                 => $validated['service_notes'] ?? $validated['notes'] ?? null,
            ]);

            return $customer;
        });
    }

    /**
     * التحقق من صحة البيانات المرسلة (Validation قواعد التحقق)
     */
    protected function validateData(array $data, bool $isStore = true)
    {
        if ($isStore) {
            $rules = [
                'name'   => 'required|string|max:255',
                'phone'  => 'required|string|max:20',
                'source' => ['required', new Enum(PotentialCustomerSource::class)],
            ];
        } else {
            $rules = [
                'name'                => 'sometimes|required|string|max:255',
                'phone'               => 'sometimes|required|string|max:20',
                'source'              => ['sometimes', 'required', new Enum(PotentialCustomerSource::class)],
                'status'              => ['sometimes', 'required', new Enum(PotentialCustomerStatus::class)],
                'reason'              => 'nullable|string',
                'next_follow_up_date' => 'nullable|date',
                'notes'               => 'nullable|string',
                
                // حقول الخدمة المستهدفة الإلزامية في حالة الـ Confirmation
                'service_type'        => ['sometimes', 'required', new Enum(\App\Enums\CompanyService::class)],
                'service_notes'       => 'nullable|string',
            ];
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}