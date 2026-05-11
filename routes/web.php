<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\UserController; // Make sure this is here!
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard & Logs
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
});

// Profile Management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// User Management (Admin area)
Route::middleware(['auth', 'verified'])->group(function () {
    // The main list page
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // The toggle action
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // The delete action
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
});

require __DIR__ . '/auth.php';