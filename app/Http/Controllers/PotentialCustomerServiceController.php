<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PotentialCustomer;
use App\Models\PotentialCustomerService;
use App\Services\PotentialCustomerServicesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PotentialCustomerServiceController extends Controller
{
    // حقن الـ Service الجديدة في الـ Constructor
    protected $customerServicesService;

    public function __construct(PotentialCustomerServicesService $customerServicesService)
    {
        $this->customerServicesService = $customerServicesService;
    }

    /**
     * Display a listing of the resource.
     */
 public function index(Request $request)
    {
        // نمرر الفلاتر ومعرف المستخدم للسيرفس وهي تتولى جلب البيانات مجهزة بالكامل
        $data = $this->customerServicesService->getFilteredServicesData(
            $request->all(), 
            auth()->id()
        );

        return view('potential-customer-services.index', $data);
    }

   
    public function store(Request $request)
    {
        $customer = PotentialCustomer::findOrFail($request->potential_customer_id);

        $this->customerServicesService->updateStatusAndLogFollowUp(
            $customer, 
            $request->all(), 
            auth()->id()
        );

        return redirect()->route('potential-customer-services.index')
            ->with('success', 'تم تأكيد حالة العميل وتفعيل الخدمة والـ Log بنجاح.');
    }
}