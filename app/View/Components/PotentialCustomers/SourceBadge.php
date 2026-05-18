<?php

namespace App\View\Components\PotentialCustomers;

use Illuminate\View\Component;
use App\Enums\PotentialCustomerSource;

class SourceBadge extends Component
{
    public $source;
    public $sourceEnum;
    public $colorClass;

    public function __construct($source)
    {
        $this->source = $source;
        $this->sourceEnum = $source instanceof PotentialCustomerSource 
            ? $source 
            : PotentialCustomerSource::tryFrom($source);
        
        $this->colorClass = match ($this->sourceEnum) {
            PotentialCustomerSource::FACEBOOK => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800/30',
            PotentialCustomerSource::INSTAGRAM => 'bg-pink-50 text-pink-700 border-pink-200 dark:bg-pink-900/20 dark:text-pink-400 dark:border-pink-800/30',
            PotentialCustomerSource::WHATSAPP => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800/30',
            PotentialCustomerSource::WEBSITE => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-900/20 dark:text-indigo-400 dark:border-indigo-800/30',
            PotentialCustomerSource::REFERRAL => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-900/20 dark:text-purple-400 dark:border-purple-800/30',
            default => 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
        };
    }

    public function render()
    {
        return view('components.potential-customers.source-badge');
    }
}