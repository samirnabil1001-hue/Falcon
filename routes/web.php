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

// الصفحة الرئيسية للموقع
Route::get('/', function () {
    return view('welcome');
});

// المسارات المحمية بتسجيل الدخول والتحقق من الحساب
Route::middleware(['auth', 'verified', 'active'])->group(function () {

    // لوحة التحكم وسجل العمليات
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');

    // إدارة الملف الشخصي للمستخدم
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // مجموعة مسارات العملاء المحتملين والخدمات التابعة لهم
    Route::prefix('potential-customers')->group(function () {

        // مسارات المتابعات (Follow Ups)
        Route::get('/follow-ups', [CustomerFollowUpController::class, 'index'])
            ->name('customer-follow-ups.index');

        Route::get('/{customer}/follow-ups-history', [CustomerFollowUpController::class, 'show'])
            ->name('customer-follow-ups.show');

        Route::post('/{customer}/follow-ups', [CustomerFollowUpController::class, 'store'])
            ->name('customer-follow-ups.store');

        Route::patch('/{potentialCustomer}/status', [PotentialCustomerController::class, 'updateStatus'])
            ->name('potential-customers.update-status');

        Route::put('/{potentialCustomer}/update-added-by', [PotentialCustomerController::class, 'updateAddedBy'])
            ->name('potential-customers.update-added-by');

        Route::resource('potential-customer-services', PotentialCustomerServiceController::class)->only([
            'index',
            'store'
        ]);
    });

    Route::resource('potential-customers', PotentialCustomerController::class);

    // مسارات إدارة المستخدمين وصلاحيات الحسابات
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::middleware(['auth', 'prevent.ceo'])->group(function () {
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');

});

// مسارات التحقق والتسجيل الافتراضية الخاصة بـ Laravel Breeze / Jetstream
require __DIR__ . '/auth.php';