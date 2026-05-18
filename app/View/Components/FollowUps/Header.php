<?php

namespace App\View\Components\FollowUps;

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
        return view('components.follow-ups.header');
    }
}