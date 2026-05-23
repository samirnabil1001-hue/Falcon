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

// مجموعة المسارات المحمية بتسجيل الدخول والتحقق من البريد + الحساب النشط
// Added 'active' middleware to this group
Route::middleware(['auth', 'verified', 'active'])->group(function () {

    // 1. لوحة التحكم والعمليات العامة
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');

    // 2. إدارة حساب المستخدم الحالي (Profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 3. مسارات المتابعات والعمليات المتقدمة للعملاء
    Route::prefix('potential-customers')->group(function () {

        // مسار العرض الرئيسي لجدول المتابعات
        Route::get('/follow-ups', [CustomerFollowUpController::class, 'index'])
            ->name('customer-follow-ups.index');

        // جلب سجل متابعات عميل محدد (AJAX)
        Route::get('/{customer}/follow-ups-history', [CustomerFollowUpController::class, 'show'])
            ->name('customer-follow-ups.show');

        // حفظ متابعة جديدة لعميل محدد من الـ Modal
        Route::post('/{customer}/follow-ups', [CustomerFollowUpController::class, 'store'])
            ->name('customer-follow-ups.store');

        // تحديث حالة العميل السريعة (تغيير الـ Status بنقرة زر)
        Route::patch('/{potentialCustomer}/status', [PotentialCustomerController::class, 'updateStatus'])
            ->name('potential-customers.update-status');
    });

    // 4. مسارات الـ CRUD القياسية للعملاء المحتملين
    Route::resource('potential-customers', PotentialCustomerController::class);

    // 5. صلاحيات الإدارة للمستخدمين والـ Roles (CEO & TeamLead)
    Route::middleware(['role:CEO,TeamLead'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });

    // 6. صلاحيات التحكم الكاملة والخاصة بالـ CEO فقط
    Route::middleware(['role:CEO'])->group(function () {
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
    });

});

Route::apiResource(
    'potential-customer-services',
    PotentialCustomerServiceController::class
);

require __DIR__ . '/auth.php';