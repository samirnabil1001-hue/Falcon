<?php

namespace App\View\Components\PotentialCustomers;

use Illuminate\View\Component;
use App\Models\PotentialCustomer;

class ConfirmedModal extends Component
{
    public $customer;
    public $route;
    public $isStoreRoute;

    // تم حذف $currentCustomer تماماً وتثبيت متغير واحد اختياري
    public function __construct($customer = null, $route = null)
    {
        // إذا لم يتم تمرير العميل، يحاول جلبه تلقائياً من الـ Route لحماية الاستدعاءات القديمة
        $this->customer = $customer ?? request()->route('potential_customer') ?? request()->route('id');
        
        // لو مفيش عميل مبعوت أو موجود في الـ URL، بنعمل Instance جديد عشان الكود ما يضربش Error
        if (!$this->customer) {
            $this->customer = new PotentialCustomer();
        }

        // لو العميل مبعوت كـ ID (رقم) مش كـ Object، بنجيبه من الداتا بيز
        if (is_numeric($this->customer)) {
            $this->customer = PotentialCustomer::find($this->customer) ?? new PotentialCustomer();
        }

        // تحديد الـ Route الافتراضي (القديم) في حال لم يتم تمرير رابط الـ Store
        $this->route = $route ?? route('potential-customers.update-status', $this->customer->id ?? 0);
        
        // الفحص الذكي لمعرفة هل الرابط رايح للـ Store الجديد أم لا
        $this->isStoreRoute = $route && str_contains($route, 'services');
    }

    public function render()
    {
        return view('components.potential-customers.confirmed-modal');
    }
}