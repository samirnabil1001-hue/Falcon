<?php

namespace App\Providers;

use App\Models\PotentialCustomer;
use App\Policies\PotentialCustomerPolicy;
use App\Services\PotentialCustomerService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // تسجيل الـ Service كـ Singleton لضمان استهلاك أمثل للذاكرة وكفاءة الأداء
        $this->app->singleton(PotentialCustomerService::class, function ($app) {
            return new PotentialCustomerService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ربط الـ Model بالـ Policy الخاصة به بشكل صريح
        Gate::policy(PotentialCustomer::class, PotentialCustomerPolicy::class);
    }
}