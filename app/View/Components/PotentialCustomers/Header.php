<?php

namespace App\View\Components\PotentialCustomers;

use Illuminate\View\Component;

class Header extends Component
{
    public $totalCount;

    public function __construct($totalCount)
    {
        $this->totalCount = $totalCount;
    }

    public function render()
    {
        return view('components.potential-customers.header');
    }
}