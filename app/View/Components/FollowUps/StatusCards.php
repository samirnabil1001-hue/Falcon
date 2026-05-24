<?php

namespace App\View\Components\PotentialCustomers;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusCards extends Component
{
    public $statusCounts;

    // بنستقبل الـ statusCounts هنا
    public function __construct($statusCounts)
    {
        $this->statusCounts = $statusCounts;
    }

    public function render(): View|Closure|string
    {
        return view('components.follow-ups.status-cards');
    }
}