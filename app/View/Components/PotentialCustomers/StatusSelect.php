<?php

namespace App\View\Components\PotentialCustomers;

use Illuminate\View\Component;
use App\Enums\PotentialCustomerStatus;

class StatusSelect extends Component
{
    public $customer;
    public $currentStatusValue;
    public $isLocked;
    public $route;

    public function __construct($customer)
    {
        $this->customer = $customer;
        $this->currentStatusValue = is_object($customer->status) 
            ? $customer->status->value 
            : $customer->status;
        
        $this->isLocked = in_array($this->currentStatusValue, [
            PotentialCustomerStatus::CONFIRMED->value,
            PotentialCustomerStatus::CANCELLED->value,
        ]);
        
        $this->route = route('potential-customers.update-status', $customer->id);
    }

    public function render()
    {
        return view('components.potential-customers.status-select');
    }
}