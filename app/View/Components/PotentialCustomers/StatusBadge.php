<?php

namespace App\View\Components\PotentialCustomers;

use Illuminate\View\Component;
use App\Enums\PotentialCustomerStatus;

class StatusBadge extends Component
{
    public $status;
    public $statusEnum;
    public $statusClasses;
    public $dotClasses;

    public function __construct($status)
    {
        $this->status = $status;
        $this->statusEnum = $status instanceof PotentialCustomerStatus 
            ? $status 
            : PotentialCustomerStatus::tryFrom($status);
        
        $this->statusClasses = match ($this->statusEnum) {
            PotentialCustomerStatus::NEW => 'bg-blue-50 text-blue-700 border-blue-200/60 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-900/40',
            PotentialCustomerStatus::CONTACTED => 'bg-amber-50 text-amber-700 border-amber-200/60 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900/40',
            PotentialCustomerStatus::CONFIRMED => 'bg-emerald-50 text-emerald-700 border-emerald-200/60 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/40',
            PotentialCustomerStatus::CANCELLED => 'bg-rose-50 text-rose-700 border-rose-200/60 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-900/40',
            default => 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
        };

        $this->dotClasses = match ($this->statusEnum) {
            PotentialCustomerStatus::NEW => 'bg-blue-500',
            PotentialCustomerStatus::CONTACTED => 'bg-amber-500',
            PotentialCustomerStatus::CONFIRMED => 'bg-emerald-500',
            PotentialCustomerStatus::CANCELLED => 'bg-rose-500',
            default => 'bg-gray-400',
        };
    }

    public function render()
    {
        return view('components.potential-customers.status-badge');
    }
}