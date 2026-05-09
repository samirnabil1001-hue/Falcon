<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
  public function index()
{
    $logs = DB::table('activity_logs')
        ->latest()
        ->paginate(20);

    return view('logs.index', compact('logs'));
}
}
