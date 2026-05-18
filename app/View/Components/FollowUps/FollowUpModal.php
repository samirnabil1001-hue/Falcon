<?php

namespace App\View\Components\FollowUps;

use Illuminate\View\Component;
use App\Models\PotentialCustomer;

class FollowUpModal extends Component
{
    public $customer;
    public $route;

    public function __construct(PotentialCustomer $customer)
    {
        $this->customer = $customer;
        $this->route = route('customer-follow-ups.store', $customer->id);
    }

    public function render()
    {
        return view('components.follow-ups.follow-up-modal');
    }
}