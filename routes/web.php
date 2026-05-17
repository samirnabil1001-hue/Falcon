<?php

use App\Enums\UserRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\PotentialCustomerController;
use App\Http\Controllers\CustomerFollowUpController;
use App\Http\Controllers\DashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// تجميع كل المسارات المحمية بـ auth و verified في مكان واحد لمنع التشتيت
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard & Logs
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // -------------------------------------------------------------------
    // مجموعات مسارات العملاء والمتابعات (الترتيب هنا هو السر لحل الـ 404)
    // -------------------------------------------------------------------
    Route::middleware(['auth', 'verified'])->group(function () {

        Route::prefix('potential-customers')->group(function () {

            // مسار العرض الرئيسي للمتابعات
            Route::get('/follow-ups', [CustomerFollowUpController::class, 'index'])
                ->name('customer-follow-ups.index');

            // 👈 المسار الجديد: جلب سجل متابعات عميل محدد (AJAX)
            Route::get('/{customer}/follow-ups-history', [CustomerFollowUpController::class, 'show'])
                ->name('customer-follow-ups.show');

            // مسار الحفظ والتحديث الخاص بالمتابعات من الـ Modal
            Route::post('/{customer}/follow-ups', [CustomerFollowUpController::class, 'store'])
                ->name('customer-follow-ups.store');

            Route::patch('/{potentialCustomer}/status', [PotentialCustomerController::class, 'updateStatus'])
                ->name('potential-customers.update-status');
        });

        Route::resource('potential-customers', PotentialCustomerController::class);
    });

    // 4. مسارات الـ CRUD للعملاء (وضعناه بالأسفل لكي لا يلتهم الروابط الثابتة)
    Route::resource('potential-customers', PotentialCustomerController::class);


    // -------------------------------------------------------------------
    // صلاحيات المستخدمين والـ Roles
    // -------------------------------------------------------------------
    Route::middleware(['role:CEO,TeamLead'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });

    Route::middleware(['role:CEO'])->group(function () {
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
    });

});

require __DIR__ . '/auth.php';