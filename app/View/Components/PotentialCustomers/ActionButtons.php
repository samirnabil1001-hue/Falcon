<?php

namespace App\View\Components\PotentialCustomers;

use Illuminate\View\Component;
use App\Enums\PotentialCustomerStatus;

class ActionButtons extends Component
{
    public $customer;
    public $currentStatusValue;

    public function __construct($customer)
    {
        $this->customer = $customer;
        $this->currentStatusValue = is_object($customer->status) 
            ? $customer->status->value 
            : $customer->status;
    }

    public function render()
    {
        return view('components.potential-customers.action-buttons');
    }
}