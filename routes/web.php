<?php

use App\Enums\UserRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\PotentialCustomerController;
use App\Http\Controllers\CustomerFollowUpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PotentialCustomerServiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'verified', 'active'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('potential-customers')->group(function () {

        Route::get('/follow-ups', [CustomerFollowUpController::class, 'index'])
            ->name('customer-follow-ups.index');

        Route::get('/{customer}/follow-ups-history', [CustomerFollowUpController::class, 'show'])
            ->name('customer-follow-ups.show');

        Route::post('/{customer}/follow-ups', [CustomerFollowUpController::class, 'store'])
            ->name('customer-follow-ups.store');

        Route::patch('/{potentialCustomer}/status', [PotentialCustomerController::class, 'updateStatus'])
            ->name('potential-customers.update-status');
    });

    Route::resource('potential-customers', PotentialCustomerController::class);

    Route::apiResource(
        'potential-customer-services',
        PotentialCustomerServiceController::class
    );

    // 6. مسارات إدارة المستخدمين والتحكم بالحسابات
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');

});

require __DIR__ . '/auth.php';