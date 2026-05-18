<?php

namespace App\View\Components\PotentialCustomers;

use Illuminate\View\Component;

class CancelledModal extends Component
{
    public $customer;
    public $route;

    public function __construct($customer)
    {
        $this->customer = $customer;
        $this->route = route('potential-customers.update-status', $customer->id);
    }

    public function render()
    {
        return view('components.potential-customers.cancelled-modal');
    }
}