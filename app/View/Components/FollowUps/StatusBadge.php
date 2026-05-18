<?php

namespace App\View\Components\FollowUps;

use Illuminate\View\Component;
use App\Enums\PotentialCustomerStatus;

class StatusBadge extends Component
{
    public $status;
    public $statusClass;

    public function __construct($status)
    {
        $this->status = $status;
        
        $statusValue = is_object($status) ? $status->value : $status;
        
        $this->statusClass = match ($statusValue) {
            'NEW' => 'bg-blue-50 text-blue-700 border-blue-100 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-900/30',
            'CONTACTED' => 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-900/30',
            'CONFIRMED' => 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-900/30',
            'CANCELLED' => 'bg-rose-50 text-rose-700 border-rose-100 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-900/30',
            default => 'bg-slate-50 text-slate-700 border-slate-100 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
        };
    }

    public function render()
    {
        return view('components.follow-ups.status-badge');
    }
}