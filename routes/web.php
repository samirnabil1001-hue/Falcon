<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\ActivityLogController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/logs', [ActivityLogController::class, 'index']);