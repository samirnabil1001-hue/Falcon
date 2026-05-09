<?php

namespace App\Http\Controllers;
 use App\Models\ActivityLog;


class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::latest()->paginate(20);

        return view('logs.index', compact('logs'));
    }
}
