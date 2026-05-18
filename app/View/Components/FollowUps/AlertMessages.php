<?php

namespace App\View\Components\FollowUps;

use Illuminate\View\Component;

class AlertMessages extends Component
{
    public $success;
    public $error;

    public function __construct($success = null, $error = null)
    {
        $this->success = $success;
        $this->error = $error;
    }

    public function render()
    {
        return view('components.follow-ups.alert-messages');
    }
}