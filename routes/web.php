<?php

use App\Enums\UserRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\PotentialCustomerController;
use App\Http\Controllers\CustomerFollowUpController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('potential-customers', PotentialCustomerController::class);
    Route::patch(
        '/potential-customers/{potentialCustomer}/status',
        [PotentialCustomerController::class, 'updateStatus']
    )->name('potential-customers.update-status');


    Route::middleware(['role:CEO,TeamLead'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });

    Route::middleware(['role:CEO'])->group(function () {
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
    });

});

Route::middleware(['auth'])->group(function () {
    // مسار تحديث الحالة وتسجيل المتابعة عبر الـ Controller الجديد
    Route::patch('/potential-customers/{customerId}/follow-up', [CustomerFollowUpController::class, 'store'])
        ->name('potential-customers.update-status');
    // تركنا الـ name كما هو "potential-customers.update-status" حتى لا تضطر لتعديل الـ action في ملفات الـ Blade القديمة.
});

require __DIR__ . '/auth.php';