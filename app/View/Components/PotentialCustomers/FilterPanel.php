<?php

namespace App\View\Components\PotentialCustomers;

use Illuminate\View\Component;

class FilterPanel extends Component
{
    public $search;
    public $dateFrom;
    public $dateTo;
    public $source;
    public $status;
    public $sortBy;
    public $sortOrder;

    public function __construct($search = null, $dateFrom = null, $dateTo = null, $source = null, $status = null, $sortBy = 'added_at', $sortOrder = 'desc')
    {
        $this->search = $search;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->source = $source;
        $this->status = $status;
        $this->sortBy = $sortBy;
        $this->sortOrder = $sortOrder;
    }

    public function render()
    {
        return view('components.potential-customers.filter-panel');
    }
}