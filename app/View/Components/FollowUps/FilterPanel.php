<?php

namespace App\View\Components\FollowUps;

use Illuminate\View\Component;
use App\Enums\PotentialCustomerStatus;

class FilterPanel extends Component
{
    public $search;
    public $status;
    public $sortBy;
    public $sortOrder;

    public function __construct($search = null, $status = null, $sortBy = 'created_at', $sortOrder = 'desc')
    {
        $this->search = $search;
        $this->status = $status;
        $this->sortBy = $sortBy;
        $this->sortOrder = $sortOrder;
    }

    public function render()
    {
        return view('components.follow-ups.filter-panel');
    }
}