<?php
// app/View/Components/UrgentCustomersTable.php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Services\PotentialCustomerService;
use Illuminate\Support\Facades\Auth;

class UrgentCustomersTable extends Component
{
    public $newCustomers;
    public $contactedCustomers;

    // استخدام الـ Service مباشرة هنا عبر الـ Dependency Injection
    public function __construct(PotentialCustomerService $customerService)
    {
        $user = Auth::user();

        $this->newCustomers = $customerService->getLatestUrgentByStatus($user, 'new', 6);

        $this->contactedCustomers = $customerService->getLatestUrgentByStatus($user, 'contacted', 6);
    }

    public function render()
    {
        return view('components.urgent-customers-table');
    }
}