<?php
// app/View/Components/UrgentCustomersTable.php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\PotentialCustomer;

class UrgentCustomersTable extends Component
{
    public $recentUrgentCustomers;

    public function __construct()
    {
        $this->recentUrgentCustomers = PotentialCustomer::whereIn('status', ['new', 'pending'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('components.urgent-customers-table');
    }
}